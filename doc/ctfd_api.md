
# CTFd web API



## Initial setup

````http
POST /setup HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/setup
Content-Type: application/x-www-form-urlencoded
Content-Length: 149
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1

nonce=79e67c31b3b45520fbb1f014ee59f0f20bf53a090f8d0ce8633a4d74222dc175&ctf_name=yolo&name=admin&email=admin%40localhost&password=toor&user_mode=teams
````

## Add a challenge

````http
POST /api/v1/challenges HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: application/json
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/new
content-type: application/json
csrf-token: 1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
origin: http://localhost:8000
Content-Length: 119
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

{"name":"ChallengeName","category":"Category1","description":"Message","value":"42","state":"hidden","type":"standard"}
````


Response:
````http
HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 563
Date: Tue, 05 Mar 2019 08:39:07 GMT

{"data": {"category": "Category1", "state": "hidden", "name": "ChallengeName", "type_data": {"templates": {"create": "/plugins/challenges/assets/create.html", "update": "/plugins/challenges/assets/update.html", "view": "/plugins/challenges/assets/view.html"}, "scripts": {"create": "/plugins/challenges/assets/create.js", "update": "/plugins/challenges/assets/update.js", "view": "/plugins/challenges/assets/view.js"}, "id": "standard", "name": "standard"}, "max_attempts": 0, "type": "standard", "id": 1, "value": 42, "description": "Message"}, "success": true}
````

## Get challenges page (full response at the end)

````http
GET /admin/challenges/1 HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/new
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1
````
=> html page


## Add a flag to a challenge

````http
POST /api/v1/flags HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: application/json
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/1
content-type: application/json
csrf-token: 1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
origin: http://localhost:8000
Content-Length: 62
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

{"content":"FLAG{flag_content}","type":"static","challenge":1}
````

Response:
````http
HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 137
Date: Tue, 05 Mar 2019 08:39:27 GMT

{"data": {"challenge_id": 1, "challenge": 1, "data": null, "content": "FLAG{flag_content}", "type": "static", "id": 1}, "success": true}
````

## Get Flag API

````http
GET /api/v1/flags/types HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/2
X-Requested-With: XMLHttpRequest
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
````

Response:

````http
HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 326
Date: Tue, 05 Mar 2019 08:55:45 GMT

{"data": {"regex": {"templates": {"create": "/plugins/flags/assets/regex/create.html", "update": "/plugins/flags/assets/regex/edit.html"}, "name": "regex"}, "static": {"templates": {"create": "/plugins/flags/assets/static/create.html", "update": "/plugins/flags/assets/static/edit.html"}, "name": "static"}}, "success": true}
````




## Update a challenge description

````http
PATCH /api/v1/challenges/1 HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: application/json
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/1
content-type: application/json
csrf-token: 1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
origin: http://localhost:8000
Content-Length: 121
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

{"name":"ChallengeName","category":"Category1","description":"Message2","value":"42","max_attempts":"0","state":"hidden"}
````

## Add a file

````http
POST /api/v1/files HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/2
X-Requested-With: XMLHttpRequest
Content-Length: 670
Content-Type: multipart/form-data; boundary=---------------------------14802192604049978022136251264
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

-----------------------------14802192604049978022136251264
Content-Disposition: form-data; name="file"; filename="go_code"
Content-Type: application/octet-stream

code --user-data-dir /root/code

-----------------------------14802192604049978022136251264
Content-Disposition: form-data; name="nonce"

1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
-----------------------------14802192604049978022136251264
Content-Disposition: form-data; name="challenge"

2
-----------------------------14802192604049978022136251264
Content-Disposition: form-data; name="type"

challenge
-----------------------------14802192604049978022136251264--
````

Response:
````http
HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 116
Date: Tue, 05 Mar 2019 08:56:05 GMT

{"data": [{"type": "challenge", "id": 1, "location": "9884265507feb03fbeded0fc2ea78dea/go_code"}], "success": true}
````

## Add Prerequisite

````
PATCH /api/v1/challenges/2 HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: application/json
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/2
content-type: application/json
csrf-token: 1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
origin: http://localhost:8000
Content-Length: 38
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

{"requirements":{"prerequisites":[1]}}
````

````
HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 570
Date: Tue, 05 Mar 2019 08:56:17 GMT

{"data": {"category": "Category1", "state": "hidden", "name": "challenge2", "type_data": {"templates": {"create": "/plugins/challenges/assets/create.html", "update": "/plugins/challenges/assets/update.html", "view": "/plugins/challenges/assets/view.html"}, "scripts": {"create": "/plugins/challenges/assets/create.js", "update": "/plugins/challenges/assets/update.js", "view": "/plugins/challenges/assets/view.js"}, "id": "standard", "name": "standard"}, "max_attempts": 0, "type": "standard", "id": 2, "value": 2, "description": "challnge 2 message"}, "success": true}
````


## Get Challenge list

````http
GET /admin/challenges HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/challenges/2
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1
````

````http
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
Content-Length: 6402
Date: Tue, 05 Mar 2019 09:00:13 GMT

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/themes/core/static/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/themes/core/static/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/themes/admin/static/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/themes/admin/static/css/vendor/font-awesome/fontawesome-fonts.css" type='text/css'>
    <link rel="stylesheet" href="/themes/admin/static/css/vendor/font-awesome/fontawesome-all.min.css" type='text/css'>
    <link rel="stylesheet" href="/themes/admin/static/css/vendor/font.css">
    <link rel="stylesheet" href="/themes/admin/static/css/jumbotron.css">
    <link rel="stylesheet" href="/themes/admin/static/css/sticky-footer.css">
    <link rel="stylesheet" href="/themes/admin/static/css/base.css">
    <script src="/themes/core/static/js/CTFd.js"></script>
    <script src="/themes/admin/static/js/vendor/promise-polyfill.min.js"></script>
    <script src="/themes/admin/static/js/vendor/fetch.min.js"></script>
    <script src="/themes/admin/static/js/vendor/moment.min.js"></script>
    <script src="/themes/admin/static/js/vendor/moment-timezone-with-data.min.js"></script>
    <script src="/themes/admin/static/js/vendor/nunjucks.min.js"></script>
    <script type="text/javascript">
        var script_root = "";
        var csrf_nonce = "1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69";
        var user_mode = "teams";
        CTFd.options.urlRoot = script_root;
        CTFd.options.csrfNonce = csrf_nonce;
    </script>
    
    <link rel="stylesheet" href="/themes/admin/static/css/challenge-board.css">

    
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a href="/" class="navbar-brand">CTFd</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#base-navbars"
                    aria-controls="base-navbars" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="base-navbars">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/statistics">Statistics</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/notifications">Notifications</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Pages</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/admin/pages">All Pages</a>
                            <a class="dropdown-item" href="/admin/pages/new">New Page</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/admin/users">Users</a></li>
                    
                    <li class="nav-item"><a class="nav-link" href="/admin/teams">Teams</a></li>
                    
                    <li class="nav-item"><a class="nav-link" href="/admin/scoreboard">Scoreboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/challenges">Challenges</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="true">Submissions</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/admin/submissions">All Submissions</a>
                            <a class="dropdown-item" href="/admin/submissions/correct">Correct Submissions</a>
                            <a class="dropdown-item" href="/admin/submissions/incorrect">Wrong Submissions</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/admin/config">Config</a></li>

                    
                    
                    
                </ul>
            </div>
        </div>
    </nav>

    


    <main role="main">
        
<div class="jumbotron">
    <div class="container">
        <h1>Challenges
            <a class="no-decoration" href="/admin/challenges/new">
                <span role="button" data-toggle="tooltip" title="Create Challenge">
                    <i class="btn-fa fas fa-plus-circle"></i>
                </span>
            </a>
        </h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <table id="challenges" class="table table-striped">
                    <thead>
                    <tr>
                        <td><b>ID</b></td>
                        <td><b>Name</b></td>
                        <td class="d-none d-md-table-cell d-lg-table-cell"><b>Category</b></td>
                        <td class="d-none d-md-table-cell d-lg-table-cell"><b>Value</b></td>
                        <td class="d-none d-md-table-cell d-lg-table-cell"><b>Type</b></td>
                        <td class="d-none d-md-table-cell d-lg-table-cell text-center"><b>State</b></td>
                    </tr>
                    </thead>
                    <tbody>
                    
                        <tr>
                            <td>1</td>
                            <td><a href="/admin/challenges/1">ChallengeName</a></td>
                            <td class="d-none d-md-table-cell d-lg-table-cell">Category1</td>
                            <td class="d-none d-md-table-cell d-lg-table-cell">42</td>
                            <td class="d-none d-md-table-cell d-lg-table-cell">standard</td>
                            <td class="d-none d-md-table-cell d-lg-table-cell text-center">
                                
                                <span class="badge badge-danger">hidden</span>
                            </td>
                        </tr>
                    
                        <tr>
                            <td>2</td>
                            <td><a href="/admin/challenges/2">challenge2</a></td>
                            <td class="d-none d-md-table-cell d-lg-table-cell">Category1</td>
                            <td class="d-none d-md-table-cell d-lg-table-cell">2</td>
                            <td class="d-none d-md-table-cell d-lg-table-cell">standard</td>
                            <td class="d-none d-md-table-cell d-lg-table-cell text-center">
                                
                                <span class="badge badge-danger">hidden</span>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </main>

    <footer class="footer pt-2">
        <div class="container text-center">
            <a href="https://ctfd.io">
                <small class="text-muted">
                    Powered by CTFd
                </small>
            </a>
            <span>
                <small class="text-muted"><br> Version 2.0.4</small>
            </span>
        </div>
    </footer>

    <script src="/themes/admin/static/js/vendor/jquery.min.js"></script>
    <script src="/themes/admin/static/js/vendor/markdown-it.min.js"></script>
    <script src="/themes/admin/static/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/themes/admin/static/js/main.js"></script>
    <script src="/themes/admin/static/js/utils.js"></script>
    <script src="/themes/admin/static/js/ezq.js"></script>
    <script src="/themes/admin/static/js/style.js"></script>
    


    
</body>

</html>
````


## Add a user

````
POST /api/v1/users HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: application/json
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/users/new
content-type: application/json
csrf-token: 1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
origin: http://localhost:8000
Content-Length: 142
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

{"name":"user1","email":"user@user.com","password":"passworduser1","country":"FR","type":"user","verified":true,"hidden":false,"banned":false}


HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 294
Date: Tue, 05 Mar 2019 09:13:31 GMT

{"data": {"website": null, "verified": true, "name": "user1", "created": "2019-03-05T09:13:31+00:00", "country": "FR", "banned": false, "email": "user@user.com", "affiliation": null, "secret": null, "bracket": null, "hidden": false, "type": "user", "id": 2, "oauth_id": null}, "success": true}
````


## Get user

````
GET /admin/users/2 HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/users/new
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1

HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
Content-Length: 30420
Date: Tue, 05 Mar 2019 09:13:31 GMT

<!DOCTYPE html>
<html>

HTTP/1.1 404 NOT FOUND
Content-Type: text/html; charset=utf-8
Content-Length: 4241
Date: Tue, 05 Mar 2019 09:17:45 GMT

<!DOCTYPE html>

<head>
[...] + html
````


# Create team

````
POST /api/v1/teams HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: application/json
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/teams/new
content-type: application/json
csrf-token: 1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
origin: http://localhost:8000
Content-Length: 113
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

{"name":"Team1","email":"team1@team.org","password":"teampassword1","country":"FR","hidden":false,"banned":false}

HTTP/1.1 200 OK
Content-Type: application/json
Content-Length: 276
Date: Tue, 05 Mar 2019 09:14:30 GMT

{"data": {"website": null, "name": "Team1", "created": "2019-03-05T09:14:30+00:00", "country": "FR", "banned": false, "email": "team1@team.org", "affiliation": null, "secret": null, "bracket": null, "members": [], "hidden": false, "id": 1, "oauth_id": null}, "success": true}
````

## User join team 

````
GET /teams/join HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/team
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1



HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
Content-Length: 4814
Date: Tue, 05 Mar 2019 09:19:16 GMT

<!DOCTYPE html>
<html>
<head>
    <title>yolo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/themes/core/static/img/favicon.ico"
          type="image/x-icon">
    <link rel="stylesheet" href="/themes/core/static/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/themes/core/static/css/vendor/font-awesome/fontawesome-fonts.css" type='text/css'>
    <link rel="stylesheet" href="/themes/core/static/css/vendor/font-awesome/fontawesome-all.min.css" type='text/css'>
    <link rel="stylesheet" href="/themes/core/static/css/vendor/font.css"  type='text/css'>
    <link rel="stylesheet" href="/themes/core/static/css/jumbotron.css">
    <link rel="stylesheet" href="/themes/core/static/css/sticky-footer.css">
    <link rel="stylesheet" href="/themes/core/static/css/base.css">
    

    
    <link rel="stylesheet" type="text/css" href="/static/user.css">
    <script src="/themes/core/static/js/vendor/promise-polyfill.min.js"></script>
    <script src="/themes/core/static/js/vendor/fetch.min.js"></script>
    <script src="/themes/core/static/js/CTFd.js"></script>
    <script src="/themes/core/static/js/vendor/moment.min.js"></script>
    <script src="/themes/core/static/js/vendor/nunjucks.min.js"></script>
    <script src="/themes/core/static/js/vendor/socket.io.min.js"></script>
    <script type="text/javascript">
        var script_root = "";
        var csrf_nonce = "1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69";
        var user_mode = "teams";
        CTFd.options.urlRoot = script_root;
        CTFd.options.csrfNonce = csrf_nonce;
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a href="/" class="navbar-brand">
                
                yolo
                
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#base-navbars"
                    aria-controls="base-navbars" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="base-navbars">
                <ul class="navbar-nav mr-auto">
                    

                    <li class="nav-item">
                        <a class="nav-link" href="/notifications">Notifications</a>
                    </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Users</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/teams">Teams</a>
                        </li>
                        
                    
                    
                        <li class="nav-item">
                            <a class="nav-link" href="/scoreboard">Scoreboard</a>
                        </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/challenges">Challenges</a>
                    </li>
                </ul>

                <hr class="d-sm-flex d-md-flex d-lg-none">

                <ul class="navbar-nav ml-md-auto d-block d-sm-flex d-md-flex">
                    
                        
                            <li class="nav-item">
                                <a class="nav-link" href="/admin">Admin</a>
                            </li>
                        
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/team">Team</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/user">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/settings">Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Logout</a>
                        </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <main role="main">
        
    <div class="jumbotron">
        <div class="container">
            <h1>Join Team</h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                
                <form method="POST">
                    <div class="form-group">
                        <label>Team Name:</label>
                        <input class="form-control" type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label>Team Password:</label>
                        <input class="form-control" type="password" name="password">
                    </div>
                    <input type="hidden" name="nonce" value="1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69">
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <button type="submit" id="submit" class="btn btn-success float-right">
                                Join
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </main>

    <footer class="footer">
        <div class="container text-center">
            <a href="https://ctfd.io">
                <small class="text-muted">Powered by CTFd</small>
            </a>
        </div>
    </footer>

    <script src="/themes/core/static/js/vendor/jquery.min.js"></script>
    <script src="/themes/core/static/js/vendor/markdown-it.min.js"></script>
    <script src="/themes/core/static/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/themes/core/static/js/style.js"></script>
    <script src="/themes/core/static/js/utils.js"></script>
    <script src="/themes/core/static/js/ezq.js"></script>
    <script src="/themes/core/static/js/events.js"></script>
    


    
</body>
</html>
````

````
POST /teams/join HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/teams/join
Content-Type: application/x-www-form-urlencoded
Content-Length: 104
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1

name=team1&password=teampassword1&nonce=1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69

HTTP/1.1 302 FOUND
Content-Type: text/html; charset=utf-8
Content-Length: 229
Location: http://localhost:8000/challenges
Date: Tue, 05 Mar 2019 09:19:28 GMT

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<title>Redirecting...</title>
<h1>Redirecting...</h1>
<p>You should be redirected automatically to target URL: <a href="/challenges">/challenges</a>.  If not click the link.
````

## import config.zip

````````
POST /admin/import HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://localhost:8000/admin/config
X-Requested-With: XMLHttpRequest
Content-Length: 14004
Content-Type: multipart/form-data; boundary=---------------------------75465957617620561561462999263
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close

-----------------------------75465957617620561561462999263
Content-Disposition: form-data; name="backup"; filename="From Rookie to Wookie.2019-03-03(3).zip"
Content-Type: application/zip

PKKHcNéþÑ*FFdb/alembic_version.json{"count": 1, "results": [{"version_num": "8369118943a1"}], "meta": {}}PKKHcNdb/awards.jsonPKKHcNá®põõdb/challenges.json{"count": 10, "results": [{"id": 1, "name": "LolCatz", "description": "Hello, jeune Padawan !\r\n\r\nLe chemin pour devenir un Jedi est long. Nous allons faire tes premiers pas ensemble...\r\n\r\n```bash\r\n$ ssh luke@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'luke' et le mot de passe 'tatooine'.\r\n\r\n```bash\r\n$ pwd\r\n```\r\nDans quel r\u00e9pertoire es tu connect\u00e9 ?\r\n\r\n\r\n```bash\r\n$ ls\r\n```\r\nQuel sont les fichiers de ce r\u00e9pertoire ?\r\n\r\n\r\n```\r\n$ cat flag1.txt\r\n```\r\nQue contient le fichier flag1.txt ?\r\n\r\nCopie ce Flag de la forme flagxxx{yyy} pour valider ce challenge !", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "null"}, {"id": 2, "name": "Crouching Tiger, Hidden Dragon", "description": "Parfois les fichiers sont cach\u00e9s en plein jour.\r\n\r\n```bash\r\n$ ssh yoda@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'yoda' et le mot de passe 'naboo'.\r\n\r\n```\r\nls -al\r\n```\r\nListe les fichiers cach\u00e9s, et affiche des infos sur le propri\u00e9taire et les groupes.\r\n", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [1]}"}, {"id": 3, "name": "Home Sweet Home", "description": "```bash\r\n$ ssh obiwan@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'obiwan' et le mot de passe 'hoth'.\r\n\r\nComment \u00e7a le frigo est vide ?\r\nEt comment est celui de la voisine  ?\r\nJete un oeil chez /home/padme...\r\n\r\nLes r\u00e9pertoires des utilisateurs sont le plus souvent dans le r\u00e9pertoire /home. Celui de l'administrateur en /root.\r\nCe sont les r\u00e9pertoires \u00e0 examiner en priorit\u00e9... Ils sont parfois autoris\u00e9s en lecture.", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [2]}"}, {"id": 4, "name": "C'est juste temporaire", "description": "Connecte toi en ssh \u00e0 10.0.0.11 avec le user dooku et le password dagobah.\r\n \r\n```bash\r\nfind / -name flag*.txt\r\n```\r\nUtilise la commande find pour rechercher tous les fichiers qui ont un nom du type flagXX.txt\r\n\r\nLes utilisateurs et administrateurs laissent souvent des fichiers interessant dans les r\u00e9pertoires temporaires comme /tmp et /var/tmp.\r\n", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [3]}"}, {"id": 5, "name": "R\u00e9sidence secondaire", "description": "```bash\r\n$ ssh jarjar@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'jarjar' et le mot de passe 'shili'.\r\n\r\nCertains comptes syst\u00e8me comme les serveur web ou de base de donn\u00e9e n'ont pas de r\u00e9pertoire du tout, ou un r\u00e9pertoire situ\u00e9 dans les donn\u00e9es de leur application.\r\n\r\nOn les trouve en derni\u00e8re colonne du fichier /etc/passwd qui est toujours en lecture par tous.\r\nOu se trouve le home du compte mace ?\r\n\r\nLes flags sont le plus souvent al\u00e9atoires... ou presque.", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [4]}"}, {"id": 6, "name": "My sweet Business Executive", "description": "```bash\r\n$ ssh quigong@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'quigong' et le mot de passe 'bespin'.\r\n\r\n```\r\n./welcome_07\r\n```\r\nLancer un fichier ex\u00e9cutable dans le r\u00e9pertoire courant avec ./xxxxx.\r\n\r\n```\r\nstrings welcome_07\r\n```\r\nDumper les cha\u00eenes de caract\u00e8res contenues dans le binaire. On y trouve g\u00e9n\u00e9ralement les noms de fichiers de config, et parfois des comptes ou de mots de passe en clair.", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [5]}"}, {"id": 7, "name": "Agent Double", "description": "```bash\r\n$ ssh grievous@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'grievous' et le mot de passe 'yavin'.\r\n\r\nUtilise les informations qui ont leak\u00e9 pour te faire passer pour leia avec la commande: su - lea", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [6]}"}, {"id": 8, "name": "Attrape moi si tu peux", "description": "```bash\r\n$ ssh han@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'han' et le mot de passe 'ando'.\r\n\r\nOutch, ce fichier est vraiment gros... Ca va prendre des heures de le lire...\r\n\r\n```\r\ngrep flag liste10.txt\r\n```\r\nFiltrons le pour n'afficher que les lignes avec le mot 'flag'.\r\n\r\nJ'ai plein de temps pour improver mon skillz \u00e0 kandykrush maintenant... C'est qui le plus malin ?\r\n", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [7]}"}, {"id": 9, "name": "Y en a un peu plus, j'vous le mets quand m\u00eame ?", "description": "```bash\r\n$ ssh c3po@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'c3po' et le mot de passe 'corellia'.\r\n\r\nLes fichiers zip sont des souvent utilis\u00e9s pour faire des sauvegardes. On y trouve parfois des fichiers de config avec des infos tr\u00e8s int\u00e9ressantes.\r\n\r\n```\r\nhead flag11.zip\r\n```\r\nAffiche les premi\u00e8res lignes du fichier zip avec head. L'ent\u00eate commence par PK, c'est bien un zip, on peut lire le nom des fichiers qu'il contient et le flag parait \u00eatre en clair. \r\nInt\u00e9ressant... L'algo ne compresse pas les tout petits fichiers...\r\n\r\n```\r\nunzip flag11.zip\r\n```\r\nIl est utile de conna\u00eetre les diff\u00e9rents outils de compression : zip, unzip, 7zip, rar,... et savoir reconna\u00eetre leurs ent\u00eates caract\u00e9ristiques.", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [8]}"}, {"id": 10, "name": "Du goudron et des plumes", "description": "```bash\r\n$ ssh finn@10.0.0.10 -p 2222\r\n```\r\nConnecte toi au serveur en 10.0.0.10, sur le port 2222, avec le user 'finn' et le mot de passe 'yavin'.\r\n\r\nLa commande tar \u00e9tait utilis\u00e9e sur les anciens syst\u00e8mes pour faire tenir toute une arborescence de fichiers en un seul fichier xxx.tar, qui \u00e9tait ensuite compress\u00e9 avec l'algorithme gzip.\r\nOn se retrouvait avec un fichier xxx.tar.gz, ou xxx.tgz.\r\n\r\nRegarde le contenu du fichier avec vi.\r\nL'\u00e9diteur est carr\u00e9ment old school, mais il est utile de le conna\u00eetre car il est pr\u00e9sent presque partout.\r\nIl faut presser la touche [Esc] pour passer en mode commande et taper \r\n```\r\n:q!\r\n```\r\npour quitter sans rien modifier.\r\n\r\nOn peut y lire le nom des fichiers, les r\u00e9pertoires et le contenu des fichiers...\r\n\r\nvim est plus sympathique, mais pas toujours install\u00e9 il reconna\u00eet le format tar et l'affiche proprement.\r\nMais l\u00e0 encore [Esc] suivi de ':q!'.\r\n", "max_attempts": 0, "value": 1, "category": "Ghost in the Shell", "type": "standard", "state": "visible", "requirements": "{\"prerequisites\": [9]}"}], "meta": {}}PKKHcN5*âódb/config.json{"count": 20, "results": [{"id": 1, "key": "ctf_version", "value": "2.0.4"}, {"id": 2, "key": "ctf_theme", "value": "core"}, {"id": 3, "key": "ctf_name", "value": "From Rookie to Wookie"}, {"id": 4, "key": "start", "value": null}, {"id": 5, "key": "user_mode", "value": "teams"}, {"id": 6, "key": "challenge_visibility", "value": "private"}, {"id": 7, "key": "registration_visibility", "value": "public"}, {"id": 8, "key": "score_visibility", "value": "public"}, {"id": 9, "key": "account_visibility", "value": "public"}, {"id": 10, "key": "end", "value": null}, {"id": 11, "key": "freeze", "value": null}, {"id": 12, "key": "verify_emails", "value": null}, {"id": 13, "key": "mail_server", "value": null}, {"id": 14, "key": "mail_port", "value": null}, {"id": 15, "key": "mail_tls", "value": null}, {"id": 16, "key": "mail_ssl", "value": null}, {"id": 17, "key": "mail_username", "value": null}, {"id": 18, "key": "mail_password", "value": null}, {"id": 19, "key": "mail_useauth", "value": null}, {"id": 20, "key": "setup", "value": "1"}], "meta": {}}PKKHcNdb/dynamic_challenge.jsonPKKHcN
db/files.jsonPKKHcN8Hçëë
db/flags.json{"count": 11, "results": [{"id": 1, "challenge_id": 1, "type": "static", "content": "flag001{F1rst_FLAG5_L1v3_F0R3v3R}", "data": ""}, {"id": 2, "challenge_id": 2, "type": "static", "content": "flag002{P0ur_v1vr3_h3ur3ux_v1v0ns_cach3s}", "data": ""}, {"id": 3, "challenge_id": 3, "type": "static", "content": "flag003{V13ns_ch3z_m01}", "data": null}, {"id": 4, "challenge_id": 4, "type": "static", "content": "flag04{M4y_th3_f0rc3_b3_w1th_y0u}", "data": null}, {"id": 5, "challenge_id": 4, "type": "static", "content": "flag05{Qu3_l4_f0rc3_s01t_4v3c_t01}", "data": null}, {"id": 6, "challenge_id": 5, "type": "static", "content": "flag06{a40053051c978701fa8bb66f110fc487}", "data": null}, {"id": 7, "challenge_id": 6, "type": "static", "content": "flag07{4_v0s_1ntu1t10ns_v0us_f13r_1l_f4ut}", "data": null}, {"id": 8, "challenge_id": 7, "type": "static", "content": "flag09{4ut4nt_3mbr4c3r_un_w00k13}", "data": null}, {"id": 9, "challenge_id": 8, "type": "static", "content": "flag10{Un3_41gu1ll3_d4ns_un3_b0tt3_d3_f01n}", "data": null}, {"id": 10, "challenge_id": 9, "type": "static", "content": "flag11{3mb4ll3z_c_3st_p3s3}", "data": null}, {"id": 11, "challenge_id": 10, "type": "static", "content": "flag12{D3t4rr3r_d3s_t4r3s}", "data": null}], "meta": {}}PKKHcN
db/hints.jsonPKKHcNdb/notifications.jsonPKKHcN2g.!!
db/pages.json{"count": 1, "results": [{"id": 1, "title": null, "route": "index", "content": "<div class=\"row\">\n    <div class=\"col-md-6 offset-md-3\">\n        <img class=\"w-100 mx-auto d-block\" style=\"max-width: 500px;padding: 50px;padding-top: 14vh;\" src=\"themes/core/static/img/logo.png\" />\n        <h3 class=\"text-center\">\n            <p>A cool CTF platform from <a href=\"https://ctfd.io\">ctfd.io</a></p>\n            <p>Follow us on social media:</p>\n            <a href=\"https://twitter.com/ctfdio\"><i class=\"fab fa-twitter fa-2x\" aria-hidden=\"true\"></i></a>&nbsp;\n            <a href=\"https://facebook.com/ctfdio\"><i class=\"fab fa-facebook fa-2x\" aria-hidden=\"true\"></i></a>&nbsp;\n            <a href=\"https://github.com/ctfd\"><i class=\"fab fa-github fa-2x\" aria-hidden=\"true\"></i></a>\n        </h3>\n        <br>\n        <h4 class=\"text-center\">\n            <a href=\"admin\">Click here</a> to login and setup your CTF\n        </h4>\n    </div>\n</div>", "draft": 0, "hidden": null, "auth_required": null}], "meta": {}}PKKHcNdb/solves.jsonPKKHcNdb/submissions.jsonPKKHcNdb/tags.jsonPKKHcN
db/teams.jsonPKKHcN²ø*ÙÙdb/tracking.json{"count": 2, "results": [{"id": 1, "type": null, "ip": "172.28.0.1", "user_id": 1, "date": "2019-03-02T17:35:31"}, {"id": 2, "type": null, "ip": "172.22.0.1", "user_id": 1, "date": "2019-03-03T09:02:22"}], "meta": {}}PKKHcNdb/unlocks.jsonPKKHcN.Eëõ
db/users.json{"count": 1, "results": [{"id": 1, "oauth_id": null, "name": "Admin", "password": "$bcrypt-sha256$2b,12$/80Xu..MMjL4JvCS2YhU7O$4bVxbT1oRRJpT9mQYr0bwV7lGmnUF96", "email": "admin@locahost", "type": "admin", "secret": null, "website": null, "affiliation": null, "country": null, "bracket": null, "hidden": 1, "banned": 0, "verified": 0, "team_id": null, "created": "2019-03-02T17:01:21"}], "meta": {}}PKKHcNéþÑ*FFdb/alembic_version.jsonPKKHcN{db/awards.jsonPKKHcNá®põõ§db/challenges.jsonPKKHcN5*âóÌdb/config.jsonPKKHcN#db/dynamic_challenge.jsonPKKHcN
K#db/files.jsonPKKHcN8Hçëë
v#db/flags.jsonPKKHcN
(db/hints.jsonPKKHcN·(db/notifications.jsonPKKHcN2g.!!
ê(db/pages.jsonPKKHcN6-db/solves.jsonPKKHcNb-db/submissions.jsonPKKHcN-db/tags.jsonPKKHcN
½-db/teams.jsonPKKHcN²ø*ÙÙè-db/tracking.jsonPKKHcNï.db/unlocks.jsonPKKHcN.Eëõ
/db/users.jsonPKÕ0
-----------------------------75465957617620561561462999263
Content-Disposition: form-data; name="nonce"

1a2702d742a47678cb8da37d3c627e4f08a56624b8636e099332ad4763505d69
-----------------------------75465957617620561561462999263--


HTTP/1.1 302 FOUND
Content-Type: text/html; charset=utf-8
Content-Length: 233
Location: http://localhost:8000/admin/config
Date: Tue, 05 Mar 2019 09:24:42 GMT

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<title>Redirecting...</title>
<h1>Redirecting...</h1>
<p>You should be redirected automatically to target URL: <a href="/admin/config">/admin/config</a>.  If not click the link.
````````

## Admin login

````
GET /admin/config HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1

HTTP/1.1 302 FOUND
Content-Type: text/html; charset=utf-8
Content-Length: 271
Location: http://localhost:8000/login?next=%2Fadmin%2Fconfig%3F
Date: Tue, 05 Mar 2019 09:24:42 GMT

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<title>Redirecting...</title>
<h1>Redirecting...</h1>
<p>You should be redirected automatically to target URL: <a href="/login?next=%2Fadmin%2Fconfig%3F">/login?next=%2Fadmin%2Fconfig%3F</a>.  If not click the link.
GET /login?next=%2Fadmin%2Fconfig%3F HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1


HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
Content-Length: 5114
Date: Tue, 05 Mar 2019 09:24:43 GMT

<!DOCTYPE html>
<html>
<head>
    <title>From Rookie to Wookie</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/themes/core/static/img/favicon.ico"
          type="image/x-icon">
    <link rel="stylesheet" href="/themes/core/static/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/themes/core/static/css/vendor/font-awesome/fontawesome-fonts.css" type='text/css'>
    <link rel="stylesheet" href="/themes/core/static/css/vendor/font-awesome/fontawesome-all.min.css" type='text/css'>
    <link rel="stylesheet" href="/themes/core/static/css/vendor/font.css"  type='text/css'>
    <link rel="stylesheet" href="/themes/core/static/css/jumbotron.css">
    <link rel="stylesheet" href="/themes/core/static/css/sticky-footer.css">
    <link rel="stylesheet" href="/themes/core/static/css/base.css">
    

    
    <link rel="stylesheet" type="text/css" href="/static/user.css">
    <script src="/themes/core/static/js/vendor/promise-polyfill.min.js"></script>
    <script src="/themes/core/static/js/vendor/fetch.min.js"></script>
    <script src="/themes/core/static/js/CTFd.js"></script>
    <script src="/themes/core/static/js/vendor/moment.min.js"></script>
    <script src="/themes/core/static/js/vendor/nunjucks.min.js"></script>
    <script src="/themes/core/static/js/vendor/socket.io.min.js"></script>
    <script type="text/javascript">
        var script_root = "";
        var csrf_nonce = "e4f3a516518d6a76badd2c23c9c9241ea8b366cedbcf3231cc250c2b7371a39c";
        var user_mode = "teams";
        CTFd.options.urlRoot = script_root;
        CTFd.options.csrfNonce = csrf_nonce;
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a href="/" class="navbar-brand">
                
                From Rookie to Wookie
                
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#base-navbars"
                    aria-controls="base-navbars" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="base-navbars">
                <ul class="navbar-nav mr-auto">
                    

                    <li class="nav-item">
                        <a class="nav-link" href="/notifications">Notifications</a>
                    </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Users</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/teams">Teams</a>
                        </li>
                        
                    
                    
                        <li class="nav-item">
                            <a class="nav-link" href="/scoreboard">Scoreboard</a>
                        </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/challenges">Challenges</a>
                    </li>
                </ul>

                <hr class="d-sm-flex d-md-flex d-lg-none">

                <ul class="navbar-nav ml-md-auto d-block d-sm-flex d-md-flex">
                    
                        
                            <li class="nav-item">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-none d-md-block d-lg-block">|</a>
                            </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <main role="main">
        
<div class="jumbotron">
    <div class="container">
        <h1>Login</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            

            <a class="btn btn-secondary btn-lg btn-block" href="/oauth">Login with Major
                League Cyber</a>

            <hr>

            <form method="post" accept-charset="utf-8" autocomplete="off" role="form" class="form-horizontal">
                <div class="form-group">
                    <label for="name-input">
                        User Name or Email
                    </label>
                    <input class="form-control" type="text" name="name" id="name-input" />
                </div>
                <div class="form-group">
                    <label for="password-input">
                        Password
                    </label>
                    <input class="form-control" type="password" name="password" id="password-input" />
                </div>
                <div class="row pt-3">
                    <div class="col-md-6">
                        <a class="float-left align-text-to-button" href="/reset_password">
                            Forgot your password?
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" id="submit" tabindex="5" class="btn btn-md btn-primary btn-outlined float-right">
                            Submit
                        </button>
                    </div>
                </div>
                <input type="hidden" name="nonce" value="e4f3a516518d6a76badd2c23c9c9241ea8b366cedbcf3231cc250c2b7371a39c">
            </form>
        </div>
    </div>
</div>

    </main>

    <footer class="footer">
        <div class="container text-center">
            <a href="https://ctfd.io">
                <small class="text-muted">Powered by CTFd</small>
            </a>
        </div>
    </footer>

    <script srcGET /login?next=%2Fadmin%2Fconfig%3F HTTP/1.1
Host: localhost:8000
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Cookie: session=fa4eb594-1ab1-4aee-9df1-ffc30d91c407
Connection: close
Upgrade-Insecure-Requests: 1="/themes/core/static/js/vendor/jquery.min.js"></script>
    <script src="/themes/core/static/js/vendor/markdown-it.min.js"></script>
    <script src="/themes/core/static/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/themes/core/static/js/style.js"></script>
    <script src="/themes/core/static/js/utils.js"></script>
    <script src="/themes/core/static/js/ezq.js"></script>
    <script src="/themes/core/static/js/events.js"></script>
    


    
</body>
</html>
````


