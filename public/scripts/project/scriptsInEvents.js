
function getGameData(runtime){

// 	var gameData = '{"email":"simone@unusualdope.com","player_number":"75","player_name":"Boris Radu","player_shirt":"tar-away"}'

	runtime.globalVars.gameData = gameData


}


const scriptsInEvents = {

	async Globalvariable_Event1_Act1(runtime, localVars)
	{
		getGameData(runtime)
	}

};

self.C3.ScriptsInEvents = scriptsInEvents;

