$(document).ready(function() {

  var sessions = [];
  var lastSessionID = 0;

  $(function() {
    GetSessions();
  });

  function GetSessions() {
    var info = {
      'lastSessionID' : lastSessionID
    };

    $.ajax({
      type: 'POST',
      url: "/arduino/php/get_sessions.php",
      data : {
        info : JSON.stringify(info)
      },
      dataType : 'json',
      success: function(response){
        console.log(response);

        if (response['isOk']) {
          var sessions = response['sessions'];

          GetSpeeds(sessions);
        }

        //setTimeout(function() {
          //GetSessions();
        //}, 5000);
      },
      error : function(response) {
        var error = response.responseText;
        console.log(error);
      }
    });
  }

  function GetSpeeds(sessions) {
    var info = {
      'sessions' : sessions
    };

    $.ajax({
      type: 'POST',
      url: "/arduino/php/get_speeds.php",
      data : {
        info : JSON.stringify(info)
      },
      dataType : 'json',
      success: function(response){
        console.log(response);

        if (response['isOk']) {
          var sessions = response['sessions'];

          GetSessionOutputs(sessions);
        }
      },
      error : function(response) {
        var error = response.responseText;
        console.log(error);
      }
    });
  }

  function GetSessionOutputs(sessions) {
    var info = {
      'sessions' : sessions
    };

    $.ajax({
      type: 'POST',
      url: "/arduino/php/session_output.php",
      data : {
        info : JSON.stringify(info)
      },
      dataType : 'json',
      success: function(response){
        console.log(response);

        if (response['isOk']) {
          var outputs = response['outputs'];

          DisplaySessionOutputs(outputs);
        }
      },
      error : function(response) {
        var error = response.responseText;
        console.log(error);
      }
    });
  }

  function DisplaySessionOutputs(outputs) {
    for (var i = 0; i < outputs.length; i++) {
      $(outputs[i]).prependTo($("#session-list")).hide().fadeIn();
    }
  }

});
