<?php
/**
 * Require core files
 */
require_once __DIR__ . '/../helpers.php';

/**
 * Setting path aliases
 */
Yii::setAlias('@base', dirname(__DIR__, 2) . '/');
Yii::setAlias('@common', dirname(__DIR__, 2) . '/common');
Yii::setAlias('@api', dirname(__DIR__, 2) . '/api');
Yii::setAlias('@jobs', dirname(dirname(__DIR__)) . '/backend/jobs');
Yii::setAlias('@frontend', dirname(__DIR__, 2) . '/frontend');
Yii::setAlias('@backend', dirname(__DIR__, 2) . '/backend');
Yii::setAlias('@console', dirname(__DIR__, 2) . '/console');
Yii::setAlias('@storage', dirname(__DIR__, 2) . '/storage');
Yii::setAlias('@tests', dirname(__DIR__, 2) . '/tests');
Yii::setAlias('@uploads', dirname(__DIR__, 2) . '/uploads');
Yii::setAlias('@api_fe', dirname(__DIR__, 2) . '/api_fe');

/**
 * Setting url aliases
 */
Yii::setAlias('@apiUrl', env('API_HOST_INFO') . env('API_BASE_URL'));
Yii::setAlias('@api_feUrl', env('API_FE_HOST_INFO') . env('API_FE_BASE_URL'));
Yii::setAlias('@frontendUrl', env('FRONTEND_HOST_INFO') . env('FRONTEND_BASE_URL'));
Yii::setAlias('@backendUrl', env('BACKEND_HOST_INFO') . env('BACKEND_BASE_URL'));
Yii::setAlias('@storageUrl', env('STORAGE_HOST_INFO') . env('STORAGE_BASE_URL'));




