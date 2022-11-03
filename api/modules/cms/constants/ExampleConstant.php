<?php

namespace api\modules\cms\constants;

class ExampleConstant{
    const GET_API_SUCCESS = 1;
    const GET_API_TOKEN_EXPIRED = 2;
    const GET_API_FORBIDDEN = 3;


    const RESOURCE_TYPE_NEWS = 'news';
    const RESOURCE_TYPE_ART = 'art';

    const USE_THUMBNAIL = 2;
    const THUMBNAIL = 'thumbnail';

    const TABLE_RESOURCE = 'tbl_resource';
    const TABLE_ARTICLE = 'tbl_article';
    const TABLE_NEWSPAPER = 'tbl_newspaper';

    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    const CREATE_ELASTIC_ARTICLE = 'create_elastic_article';
    const CREATE_ELASTIC_NEWSPAPER = 'create_elastic_newspaper';
    const CREATE_ELASTIC_RESOURCE = 'create_elastic_resource';
    const CREATE_ELASTIC_SLUG_NAME = 'create_elastic_slug_name';
    const CREATE_ELASTIC_SLUG_ACTION = 'create_elastic_slug_action';
}