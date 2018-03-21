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

  function GetSpeeds(tempSessions) {
    var info = {
      'sessions' : tempSessions
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
          sessions = response['sessions'];

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

  $(document).on('click', '.session-title', function() {
    var sessionID = $(this).attr('sessionid');

    ShowSessionInfo(sessionID);
    ShowGraph(sessionID);
  });

  function ShowSessionInfo(sessionID) {
    $("#graph-block-" + sessionID).removeClass("hidden").hide().slideDown();
  }

  function ShowGraph(sessionID) {
    var speed;
    var speeds = [];
    var data = [];
    var dataSeries = { type: "line" };
    var dataPoints = [];

    for (var i = 0; i < sessions.length; i++) {
      var curSession = sessions[i];

      if (curSession['sessionID'] == sessionID) {
        speeds = curSession['speeds'];
        console.log(curSession);
        console.log(speeds);
        break;
      }
    }

    for (var i = 0; i < speeds.length; i++) {
      speed = parseFloat(speeds[i]['speedKPH']);

      dataPoints.push({
        x: i * 2,
        y: speed
      });
    }

    dataSeries.dataPoints = dataPoints;
    data.push(dataSeries);

    var options = {
      zoomEnabled: true,
      animationEnabled: true,
      title: {
        text: "Session #" + sessionID + " Data"
      },
      axisY: {
        includeZero: false
      },
      data: data  // random data
    };

    $("#graph-" + sessionID).CanvasJSChart(options);
  }

});
