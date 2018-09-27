<?php
/*
NGINX sends headers as
Auth-User: somuser
Auth-Pass: somepass
On my php app server these are seen as
HTTP_AUTH_USER and HTTP_AUTH_PASS
*/
if (!isset($_SERVER["HTTP_AUTH_USER"] ) || !isset($_SERVER["HTTP_AUTH_PASS"] )){
  fail();
}

$username=$_SERVER["HTTP_AUTH_USER"] ;
$userpass=$_SERVER["HTTP_AUTH_PASS"] ;
$protocol=$_SERVER["HTTP_AUTH_PROTOCOL"] ;

// default backend port
$backend_port=143;

if ($protocol=="imap") {
  $backend_port=993;
}

if ($protocol=="smtp") {
  $backend_port=465;
}

// NGINX likes ip address so if your
// application gives back hostname, convert it to ip address here
$backend_ip["mailhost01"] ="173.203.187.10";

// Authenticate the user or fail
if (!authuser($username,$userpass)){
  fail();
  exit;
}

// Get the server for this user if we have reached so far
$userserver=getmailserver($username);

// Get the ip address of the server
// We are assuming that you backend returns hostname
// We try to get the ip else return what we got back
$server_ip=(isset($backend_ip[$userserver]))?$backend_ip[$userserver] :$userserver;

// Pass!
pass($server_ip, $backend_port);

//END

function authuser($user,$pass){
  // password characters encoded by nginx:
  // " " 0x20h (SPACE)
  // "%" 0x25h
  // see nginx source: src/core/ngx_string.c:ngx_escape_uri(...)
  $pass = str_replace('%20',' ', $pass);
  $pass = str_replace('%25','%', $pass);

  // put your logic here to authen the user to any backend
  // you want (datbase, ldap, etc)
  // for example, we will just return true;
  return true;
}

function getmailserver($user){
    return "173.203.187.10";
}

function fail(){
  header("Auth-Status: Invalid login or password");
  exit;
}

function pass($server,$port){
  header("Auth-Status: OK");
  header("Auth-Server: 173.203.187.10");
  header("Auth-Port: 993");
  exit;
}


