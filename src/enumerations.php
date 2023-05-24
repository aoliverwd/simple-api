<?php

// Set namespace
namespace AOWD\SimpleAPI;

// Set status enumerations
enum Status: string
{
    case Success = 'success';
    case Fail = 'fail';
    case Error = 'error';
}

// HTTP methods enumerations
enum Methods: string
{
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case PATCH = 'patch';
    case DELETE = 'delete';
}

enum URLSegments
{
    case Start;
    case End;
    case Index;
}
