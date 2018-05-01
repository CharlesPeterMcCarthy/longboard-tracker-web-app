$(document).ready(function() {

  var sessions = [];
  var lastSessionID = 0;

  $(function() {
    GetSessions();
  });

  function PromptLogin() {
    $("#login-modal-trigger-btn").click();
  }

  $(document).on('click', '#login-btn', function() {
    var email = $("#login-email").val();
    var pass = $("#login-pass").val();

    AttemptLogin(email, pass);
  });

  function AttemptLogin(email, pass) {
    var info = {
      'email' : email,
      'pass' : pass
    };

    $.ajax({
      type: 'POST',
      url: "php/login.php",
      data : {
        info : JSON.stringify(info)
      },
      dataType : 'json',
      success: function(response){
        console.log(response);

        if (response['isOk']) {

        }
      },
      error : function(response) {
        var error = response.responseText;
        console.log(error);
      }
    });
  }

  function GetSessions() {
    var info = {
      'lastSessionID' : lastSessionID
    };

    $.ajax({
      type: 'POST',
      url: "php/get_sessions.php",
      data : {
        info : JSON.stringify(info)
      },
      dataType : 'json',
      success: function(response){
        console.log(response);

        if (response['isOk']) {
          if (response['hasSessions']) {
            var sessions = response['sessions'];

            SetLastSessionID(sessions);
            GetSpeeds(sessions);
            RemoveNoSessionsNotice();
          } else if (response['isLoggedIn'] != undefined && !response['isLoggedIn']) {
            PromptLogin();
          }
        }

        setTimeout(function() {
          GetSessions();
        }, 10000);
      },
      error : function(response) {
        var error = response.responseText;
        console.log(error);
      }
    });
  }

  function SetLastSessionID(sessions) {
    for (var i = 0; i < sessions.length; i++) {
      var curSession = sessions[i];

      if (curSession['sessionID'] > lastSessionID) {
        lastSessionID = curSession['sessionID'];
      }
    }
  }

  function RemoveNoSessionsNotice() {
    if ($("#no-sessions-notice").is(':visible')) {
      $("#no-sessions-notice").fadeOut();
    }
  }

  function GetSpeeds(tempSessions) {
    var info = {
      'sessions' : tempSessions
    };

    $.ajax({
      type: 'POST',
      url: "php/get_speeds.php",
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
      url: "php/session_output.php",
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
      data: data
    };

    $("#graph-" + sessionID).CanvasJSChart(options);
  }

  $(document).on('click', '#logout-btn', function() {
    window.location = "php/logout.php";
  });

});
