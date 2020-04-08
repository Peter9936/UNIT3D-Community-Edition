<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */
if (!function_exists('appurl')) {
    function appurl()
    {
        return config('app.url');
    }
}

if (!function_exists('hrefProfile')) {
    function hrefProfile($user)
    {
        $appurl = appurl();

        return sprintf('%s/users/%s', $appurl, $user->username);
    }
}

if (!function_exists('hrefArticle')) {
    function hrefArticle($article)
    {
        $appurl = appurl();

        return sprintf('%s/articles/%s', $appurl, $article->id);
    }
}

if (!function_exists('hrefTorrent')) {
    function hrefTorrent($torrent)
    {
        $appurl = appurl();

        return sprintf('%s/torrents/%s', $appurl, $torrent->id);
    }
}

if (!function_exists('hrefRequest')) {
    function hrefRequest($torrentRequest)
    {
        $appurl = appurl();

        return sprintf('%s/requests/%s', $appurl, $torrentRequest->id);
    }
}

if (!function_exists('hrefPoll')) {
    function hrefPoll($poll)
    {
        $appurl = appurl();

        return sprintf('%s/polls/%s', $appurl, $poll->id);
    }
}

if (!function_exists('hrefPlaylist')) {
    function hrefPlaylist($playlist)
    {
        $appurl = appurl();

        return sprintf('%s/playlists/%s', $appurl, $playlist->id);
    }
}

if(!function_exists('getLanguageFlag')) {
  function getLanguageFlag($fullLanguage) {
    switch ($fullLanguage) {
      case 'English':
        $flag = "en";
        break;
      case 'Arabic':
        $flag = "ar";
        break;
      case 'Bulgarian':
        $flag = "bg";
        break;
      case 'Chinese':
        $flag = "zh-CN";
        break;
      case 'Croatian':
        $flag = "hr";
        break;
      case 'Czech':
        $flag = "cs";
        break;
      case 'Danish':
        $flag = "da";
        break;
      case 'Dutch':
        $flag = "nl";
        break;
      case 'Estonian':
        $flag = "et";
        break;
      case 'Finnish':
        $flag = "fi";
        break;
      case 'French':
        $flag = "fr";
        break;
      case 'German':
        $flag = "de";
        break;
      case 'Greek':
        $flag = "el";
        break;
      case 'Hebrew':
        $flag = "he";
        break;
      case 'Hindi':
        $flag = "hi";
        break;
      case 'Hungarian':
        $flag = "hu";
        break;
      case 'Icelandic':
        $flag = "is";
        break;
      case 'Indonesian':
        $flag = "id";
        break;
      case 'Italian':
        $flag = "it";
        break;
      case 'Japanese':
        $flag = "ja";
        break;
      case 'Korean':
        $flag = "ko";
        break;
      case 'Latvian':
        $flag = "lv";
        break;
      case 'Lithuanian':
        $flag = "lt";
        break;
      case 'Norwegian':
      case 'Norwegian Bokmal':
        $flag = "no";
        break;
      case 'Persian':
        $flag = "fa";
        break;
      case 'Polish':
        $flag = "pl";
        break;
      case 'Portuguese':
        $flag = "pt";
        break;
      case 'Romanian':
        $flag = "ro";
        break;
      case 'Russian':
        $flag = "ru";
        break;
      case 'Serbian':
        $flag = "sr";
        break;
      case 'Slovak':
        $flag = "sk";
        break;
      case 'Slovenian':
        $flag = "sl";
        break;
      case 'Spanish':
        $flag = "es";
        break;
      case 'Swedish':
        $flag = "sv";
        break;
      case 'Thai':
        $flag = "th";
        break;
      case 'Turkish':
        $flag = "tr";
        break;
      case 'Ukrainian':
        $flag = "uk";
        break;
      case 'Vietnamese':
        $flag = "vi";
        break;
      default:
        $flag = null;
        break;
    }
    if ($flag != null) {
      $flagUrl = '/img/flags/' . $flag . '.png';
    } else {
      $flagUrl = null;
    }
    return $flagUrl;
  }
}
