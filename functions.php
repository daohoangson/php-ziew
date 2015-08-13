<?php

/**
 * @param $dirPath
 * @param null $rootPath
 * @return array
 * @throws Exception
 */
function findZipPaths($dirPath, $rootPath = null)
{
    $zipPaths = array();

    $dirPath = rtrim($dirPath, '/');
    if (empty($dirPath)) {
        // TODO: support going from root
        $dirPath = '.';
    }
    if ($rootPath === null) {
        $rootPath = $dirPath;
    }

    if ($entryPaths = glob(sprintf('%s/*', $dirPath))) {
        $subDirPaths = array();

        foreach ($entryPaths as $entryPath) {
            if (is_dir($entryPath)) {
                $subDirPaths[] = $entryPath;
            } elseif (strtolower(substr($entryPath, -4)) === '.zip') {
                $relativePath = preg_replace('#^' . preg_quote($rootPath, '#') . '#', '', $entryPath, -1, $count);
                if ($count !== 1) {
                    throw new Exception(sprintf('Unable to extract $relativePath from %s ($rootPath=%s)', $entryPath, $rootPath));
                }

                $zipPaths[] = ltrim($relativePath, '/');
            }
        }

        foreach ($subDirPaths as $subDirPath) {
            $zipPaths = array_merge($zipPaths, findZipPaths($subDirPath, $rootPath));
        }
    }

    return $zipPaths;
}

/**
 * @param $zipPath
 * @return string|null
 */
function getZipPassword($zipPath)
{
    $password = getCached(getZipPasswordCacheKey($zipPath));

    if ($password === null) {
        return getParam('password');
    }

    return $password;
}

function setZipPassword($zipPath, $password)
{
    return setCache(getZipPasswordCacheKey($zipPath), $password);
}

/**
 * @param $zipPath
 * @return string
 */
function getZipPasswordCacheKey($zipPath)
{
    return sprintf('zipPassword-%s', preg_replace('#[^A-Za-z0-9]#', '', getZipPathFull($zipPath)));
}

/**
 * @param $zipPath
 * @param $password
 * @return array
 * @throws Exception
 */
function extractZip($zipPath, $password)
{
    $tmp = tempnam(sys_get_temp_dir(), 'extractZip');
    if (file_exists($tmp)) {
        unlink($tmp);
    }
    mkdir($tmp);
    if (!is_dir($tmp)) {
        throw new Exception(sprintf('mkdir(%s) failed', $tmp));
    }

    $zip = new ZipArchive();
    if ($zip->open(getZipPathFull($zipPath)) !== true) {
        throw new Exception(sprintf('ZipArchive::open(%s) failed', $zipPath));
    }

    $zip->setPassword($password);
    $extracted = $zip->extractTo($tmp);
    $zip->close();
    if (!$extracted) {
        throw new InvalidArgumentException(sprintf('ZipArchive::extractTo(%s) with $password=%s failed', $tmp, $password));
    }

    return glob(sprintf('%s/*', $tmp));
}

/**
 * @param $tmpFilePath
 * @return string
 */
function getMimeType($tmpFilePath)
{
    $mimeType = 'application/octet-stream';
    $extension = strtolower(substr($tmpFilePath, strrpos($tmpFilePath, '.') + 1));
    switch ($extension) {
        case 'gif':
            $mimeType = 'image/gif';
            break;
        case 'jpg':
        case 'jpeg':
            $mimeType = 'image/jpeg';
            break;
        case 'mov':
            $mimeType = 'video/quicktime';
            break;
        case 'mp4':
            $mimeType = 'video/mp4';
            break;
        case 'png':
            $mimeType = 'image/png';
            break;
        case 'pxm':
            $mimeType = 'application/pxm';
            break;
    }

    return $mimeType;
}

function tryCache($function, $args, $callIfNotCached = true)
{
    $cacheKey = sprintf('%s-%s', $function, md5(serialize($args)));
    $result = getCached($cacheKey);

    if ($result === null && $callIfNotCached) {
        $result = call_user_func_array($function, $args);
        setCache($cacheKey, $result);
    }

    return $result;
}

function getZipPathFull($zipPath)
{
    return sprintf('%s/%s', getParam('root'), $zipPath);
}

/**
 * @param $key
 * @return string
 */
function getParam($key)
{
    switch ($key) {
        case 'action':
        case 'mime-type':
        case 'no-cache':
        case 'path':
            if (!empty($_REQUEST[$key])) {
                return $_REQUEST[$key];
            } else {
                return '';
            }
        case 'memcache_host':
        case 'memcache_port':
        case 'root':
            if (isset($_REQUEST[$key])) {
                return $_REQUEST[$key];
            } elseif (!empty($GLOBALS['config'][$key])) {
                return $GLOBALS['config'][$key];
            } else {
                return '.';
            }
        case 'password':
            if (isset($_REQUEST['password'])) {
                return base64_decode($_REQUEST['password']);
            }

            return '';
    }
}

/**
 * @param $action
 * @param array $params
 * @return string
 */
function buildUrl($action, array $params = array())
{
    $url = sprintf('index.php?action=%s', urlencode($action));

    $root = getParam('root');
    $configRoot = '';
    if (!empty($GLOBALS['config']['root'])) {
        $configRoot = $GLOBALS['config']['root'];
    }
    if ($root !== $configRoot) {
        $url .= sprintf('&root=%s', urlencode($root));
    }

    $password = getParam('password');
    if (!empty($password)) {
        $url .= sprintf('&password=%s', urlencode(base64_encode($password)));
    }

    foreach ($params as $key => $value) {
        $url .= sprintf('&%s=%s', $key, urlencode($value));
    }

    switch ($action) {
        case 'stream':
        case 'thumbnail':
        case 'vthumbnail':
            break;
        default:
            $url .= sprintf('&_=%d', time());
    }

    return $url;
}

/**
 * @param $name
 * @return mixed|null
 */
function getVariable($name)
{
    $variables = getVariables();

    if (!empty($variables->$name)) {
        return $variables->$name;
    }

    return null;
}

function setVariable($name, $value)
{
    $variables = getVariables();

    $variables->$name = $value;
}

/**
 * @return stdClass
 */
function getVariables()
{
    static $variables = null;

    if ($variables === null) {
        $variables = new stdClass();

        // default values
        $variables->title = 'php-ziew';
    }

    return $variables;
}

/**
 * @param $key
 * @return mixed|null
 * @throws Exception
 */
function getCached($key)
{
    if (!!getParam('no-cache')) {
        return null;
    }

    $cached = getCacheConnection()->get($key);
    if (empty($cached)) {
        return null;
    }

    return unserialize($cached);
}

function setCache($key, $value, $ttl = 0)
{
    return getCacheConnection()->set($key, serialize($value), $ttl);
}

/**
 * @return Memcache
 * @throws Exception
 */
function getCacheConnection()
{
    static $conn = null;

    if ($conn === null) {
        $conn = new Memcache;
        if (!$conn->connect(getParam('memcache_host'), getParam('memcache_port'))) {
            throw new Exception('Memcache::connect failed');
        }
    }

    return $conn;
}