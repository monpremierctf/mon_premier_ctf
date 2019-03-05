
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


