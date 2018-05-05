# longboard_web_app
PHP & jQuery Web Application to view skate data that was calculated with an Arduino Yún.

The user should be able to get to this app via the web browser or via a link they received in an email which is sent to them via the API ([API GitHub Repo](https://github.com/CharlesPeterMcCarthy/longboard_api))

## How It Works
- Basic info shown: Skate ID, skate start and end time.
- More info: Skate length (seconds) and skate distance.
- JavaScript Chart to display skate speeds.
- New skate sessions are loaded into view.

- When the user arrives to the main page (`skate_sessions.php`), they will need to login to view their skate sessions
- When clicking the login button at the top of the page, a modal will drop down with input fields for the user email and password
- The user email and password is different from the device name and password
- When the user has successfully logged in, all of their previous skate sessions populate the screen.
- Each session shows some brief information:
  - Skate session ID number
  - The start date-time
  - The end date-time
- If the user has arrived here via an email link, the corresponding skate session will be highlighted
- When the user clicks on a certain session, it opens up and shows more information:
  - Total skate length (in seconds)
  - Total skate distance
  - A graph plotting the speed logs against the time

### To Use
**/php/db_conn.php**
  - Change `{{SERVER_NAME}}` to the Server Name
  - Change `{{USER_NAME}}` to the MySQL User Name
  - Change `{{PASSWORD}}` to the MySQL User Password
  - Change `{{DB_NAME}}` to the MySQL Database Name
*(The database information should be the same as the database information for the [API](https://github.com/CharlesPeterMcCarthy/longboard_api))*

![Login Screen Image](images/login.png?raw=true "Login Screen")

![All Sessions Image](images/sessions.png?raw=true "All Sessions")

![Session #178 Image](images/session178.png?raw=true "Session 178")
