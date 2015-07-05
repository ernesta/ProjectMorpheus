(function($) {
	// CONSTANTS
	var IMG_URL = "http://media.steampowered.com/steamcommunity/public/images/apps/"
	
	
	// VARIABLES
	var games = {}
	var topAll = []
	var topRecent = []
	
	
	// FLOW
	requestOwnedGames()
	
	
	// FUNCTIONS
	// Sends an AJAX request to a PHP file which in turn sends a request for a list of games to Steam.
	function requestOwnedGames() {
		var request = $.ajax({
			url: "steam.php",
			type: "POST"
		}).done(displayGames)
	}
	
	// Processes the data received from Steam and displays it.
	function displayGames(data) {
		processGames(data)
		
		topAllGames = getTopGames(topAll)
		displayFeaturedGames(topAllGames)
		
		displayAllGames()
		initSorting()
	}
	
	// Reads the returned JSON, adds the resulting data to games (a dictionary of games indexed by game ID) and topAll/topRecent (lists of dictionaries indexed by the number of minutes corresponding games have been played).
	function processGames(data) {
		var response = $.parseJSON(data)["response"]
		var gameList = response["games"]
		
		for (var i = 0; i < gameList.length; i++) {
			var game = gameList[i]
			var gameID = game["appid"]
			var playedAll = parseInt(game["playtime_forever"])
			var playedWeeks = game["playtime_2weeks"] ? parseInt(game["playtime_2weeks"]) : 0
			
			// All games, even those that have never been played, are added to the lists. Having zero-time games is useful, since this allows for a quick access to all games that have not been (recently or at all) tried by the user.
			if (!(playedAll in topAll)) {
				topAll[playedAll] = []
			}
			topAll[playedAll].push(gameID)
			
			if (!(playedWeeks in topRecent)) {
				topRecent[playedWeeks] = []
			}
			topRecent[playedWeeks].push(gameID)
			
			games[gameID] = {
				"icon": game["img_icon_url"],
				"logo": game["img_logo_url"],
				"name": game["name"],
				"playedAll": playedAll,
				"playedWeeks": playedWeeks
			}
		}
	}
	
	// Creates a sorted array of games, with the game that has been played the longest at the beginning of the array.
	function getTopGames(counts) {
		topGames = []
	
		playedCounts = Object.keys(counts)
		playedCounts.sort(function(a, b) {
			return parseInt(b) - parseInt(a)
		})
		
		for (var i = 0; i < playedCounts.length; i++) {
			var count = playedCounts[i]
			var playedGames = counts[count]
			
			if (count == 0) break
			
			for (var j = 0; j < playedGames.length; j++) {
				topGames.push(playedGames[j])
			}
		}	
		
		return topGames
	}
	
	// Displays four featured games (logos, titles, playing time).
	function displayFeaturedGames(featuredGames) {
		$(".feature").each(function(i) {
			var game = games[featuredGames[i]]
			
			var logoURL = IMG_URL + topAllGames[i] + "/" + game["logo"] + ".jpg"
			var hrsPlayed = Math.floor(game["playedAll"] / 60)
			var minsPlayed = game["playedAll"] - hrsPlayed * 60
			
			$(this).append("<img src=" + logoURL + " />")
			$(this).append("<h4>" + game["name"] + "</h4>")
			$(this).append("<span class='text-muted'>" + hrsPlayed + " hrs " + minsPlayed + " mins</span>")
		})
	}
	
	// Populates the games list.
	function displayAllGames() {
		var rows = ""
		for (var ID in games) {
			var game = games[ID]
			
			var icon = ""
			if (game["icon"]) {
				var icon = "<img src=" + IMG_URL + ID + "/" + game["icon"] + ".jpg />"
			}
			
			rows +=
				"<tr>" +
				"<td>" + icon + "</td>" +
				"<td class='name'>" + game["name"] + "</td>" +
				"<td></td>" +
				"<td></td>" +
				"<td></td>" +
				"<td><span style='display:none;' class='playedAll'>" + game["playedAll"] + "</span>" + getPrettyTime(game["playedAll"]) + "</td>" +
				"</tr>"
		}
		
		$(".table").append(rows)
	}
	
	
	function initSorting() {
		var options = {
			valueNames: ["name", "playedAll"],
			page: 10000
		}
		
		var list = new List("games", options)
		list.sort("name")
	}
	
	
	// Returns pretty time in hrs:mins format.
	function getPrettyTime(minutes) {
		var hours = Math.floor(minutes / 60)
		var mins = minutes % 60
		
		sHours = "" + hours
		sMins = mins > 9 ? "" + mins : "0" + mins
		
		return sHours + ":" + sMins
	}
	
	function getTimeInMinutes(prettyTime) {
		var pieces = prettyTime.split(":")
		var hours = parseInt(pieces[0])
		var mins = parseInt(pieces[1])

		return hours * 60 + mins
	}
	
	function sortTime(a, b, options) {
		mins1 = getTimeInMinutes(a.values()[options.valueName])
		mins2 = getTimeInMinutes(b.values()[options.valueName])
		
		if (mins1 > mins2) {
			return 1
		} else if (mins1 < mins2) {
			return -1
		} else {
			return 0
		}
	}
})(jQuery)