<?php
/*
Copyright 2013 Google Inc. All Rights Reserved.

This file is part of the AdSense Plugin.

The AdSense Plugin is free software:
you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation,
either version 2 of the License, or (at your option) any later version.

The AdSense Plugin is distributed in the hope that it
will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License
along with the AdSense Plugin.
If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('ABSPATH')) {
  exit;
}

require_once 'ClassAutoloader.php';

/**
 * A singleton class that manages the plugin configuration, stored using
 * the WordPress options system.
 */
class GooglePublisherPluginConfiguration {

  /** Name used to store options in WordPress option table. */
  const OPTIONS_NAME = 'GooglePublisherPlugin';

  /** Name used to store the plugin's version in WordPress option table. */
  const PLUGIN_VERSION_KEY = 'GooglePublisherPlugin_Version';

  /** Keys used for root entries in options. */
  const SITE_VERIFICATION_TOKEN_KEY = 'token';
  const SITE_ID_KEY = 'siteId';
  const TAGS_CONFIGURATION_KEY = 'tags';
  const NOTIFICATION_KEY = 'notification';
  const UPDATE_SUPPORT_KEY = 'updateSupport'; // 'true' or 'false'.

  const CMS_COMMAND_SUCCESS = 'GooglePublisherPluginCmsCommandStatus::OK';

  /**
   * Gets the stored site config.
   *
   * @return array An array of tags or an empty array if there is no
   *     configuration.
   */
  public function get() {
    return self::getValueOrDefault(self::TAGS_CONFIGURATION_KEY, array());
  }

  /**
   * Gets a tag to embed on the given page type, at the given position.
   *
   * @param string $page_type The page type to get the tag for.
   * @param string $position The position to get the tag for.
   * @param string $current_theme_hash The current theme hash.
   * @return string The tag to embed, or an empty string if none is set.
   */
  public function getTag($page_type, $position, $current_theme_hash) {
    $result = '';
    foreach (self::get() as $tag) {
      if (array_key_exists('pageType', $tag) &&
          array_key_exists('position', $tag) && $tag['position'] == $position &&
          array_key_exists('code', $tag)) {
        // If an expected theme hash was specified then skip this tag if the
        // current theme hash doesn't match.
        if (array_key_exists('expectedCmsThemeHash', $tag) &&
            $tag['expectedCmsThemeHash'] !== $current_theme_hash) {
          continue;
        }
        // A matching pageType should take priority over the 'default' tag. Only
        // break if a matching pageType has been found.
        if ($tag['pageType'] == $page_type) {
          $result = $tag['code'];
          break;
        } else if ($tag['pageType'] == 'default') {
          $result = $tag['code'];
        }
      }
    }
    return $result;
  }

  /**
   * Stores the latest site config. This will fail if the option has not been
   * set.
   *
   * @return string 'GooglePublisherPluginCmsCommandStatus::OK' on success,
   *     or a string describing the error on failure.
   */
  public function updateConfig($jsonEncodedConfig) {
    $decoded = json_decode($jsonEncodedConfig, true);
    if ($decoded === null) {
      return 'Failed to decode site config (invalid JSON)';
    }
    if (!is_array($decoded)) {
      return 'Unexpected site config received (array expected)';
    }

    if (array_key_exists('tags', $decoded)) {
      $tags = $decoded['tags'];
      if (!is_array($tags)) {
        return 'Unexpected tags received (array expected)';
      }
    } else {
      $tags = array();
    }

    if (array_key_exists('notification', $decoded)) {
      $notification = $decoded['notification'];
      if (!is_array($notification)) {
        return 'Unexpected notification received (array expected)';
      }
      if (array_key_exists('status', $notification)) {
        $notificationStatus = $notification['status'];
      } else {
        return 'Notification status missing';
      }
    }

    $option = get_option(self::OPTIONS_NAME);
    if (empty($option)) {
      return 'No existing configuration';
    }
    if (isset($tags)) {
      $option[self::TAGS_CONFIGURATION_KEY] = $tags;
    }
    if (isset($notificationStatus)) {
      $option[self::NOTIFICATION_KEY] = $notificationStatus;
    }
    update_option(self::OPTIONS_NAME, $option);
    return self::CMS_COMMAND_SUCCESS;
  }

  /**
   * Writes the site verification token to the configuration. The configuration
   * allows multiple tokens to be set.
   *
   * @param string $token The token to add.
   * @return boolean True on success, false otherwise.
   */
  public function writeSiteVerificationToken($token) {
    $option = get_option(self::OPTIONS_NAME);
    if (empty($option)) {
      $option = array();
    }
    if (!isset($option[self::SITE_VERIFICATION_TOKEN_KEY])) {
      $option[self::SITE_VERIFICATION_TOKEN_KEY] = array();
    }
    array_push($option[self::SITE_VERIFICATION_TOKEN_KEY], $token);
    if (update_option(self::OPTIONS_NAME, $option)) {
      /*
       * Clears the WordPress object cache whenever we change the site
       * verification token.
       *
       * (http://codex.wordpress.org/Class_Reference/WP_Object_Cache).
       * Usually, WP object cache is cleared after each request. But some
       * cache plugins, e.g., batcache, keep cached object persistent across
       * requests. The cache buster URL parameter does not help in this
       * situation. But it does help over the page level cache, e.g., in W3
       * Total Cache.
       *
       * Plugins are free to cache under whatever namespace and key, there is
       * no way for us to know which cached object corresponds to the HTML
       * head. So we have to clear everything.
       */
      wp_cache_flush();
      return true;
    }

    return false;
  }

  /**
   * Gets the site verification tokens from the configuration.
   *
   * @return array An array of tokens, or an empty array if none was set.
   */
  public function getSiteVerificationTokens() {
    return self::getValueOrDefault(self::SITE_VERIFICATION_TOKEN_KEY, array());
  }

  /**
   * Writes the site ID to the configuration.
   *
   * @param string $id The site ID to set.
   * @return boolean True on success, false otherwise.
   */
  public function writeSiteId($id) {
    $option = get_option(self::OPTIONS_NAME);
    if (empty($option)) {
      $option = array();
    }

    if (isset($option[self::SITE_ID_KEY])
        && $option[self::SITE_ID_KEY] === $id) {
      return true;
    }

    $option[self::SITE_ID_KEY] = $id;
    if (update_option(self::OPTIONS_NAME, $option)) {
      return true;
    }

    return false;
  }

  /**
   * Gets the site ID from the configuration.
   *
   * @return string|null The site ID, or null if none was set.
   */
  public function getSiteId() {
    return self::getValueOrDefault(self::SITE_ID_KEY, null);
  }

  /**
   * Gets the notification status.
   *
   * @return string The notification status, or the new install notification if
   *     none was set.
   */
  public function getNotification() {
    return self::getValueOrDefault(self::NOTIFICATION_KEY,
        GooglePublisherPluginNotifier::NEW_INSTALL_NOTIFICATION);
  }

  /**
   * Writes the notification status.
   *
   * @param string $notification The notification status to set.
   * @return boolean True on success, false otherwise.
   */
  public function writeNotification($notification) {
    return self::trySetValue(self::NOTIFICATION_KEY, $notification);
  }

  /**
   * Gets the update support status.
   *
   * @return string|null The update support status, or null if none was set.
   */
  public function getUpdateSupport() {
    return self::getValueOrDefault(self::UPDATE_SUPPORT_KEY, null);
  }

  /**
   * Writes the update support status.
   *
   * @param string $updateSupport The update support status to set.
   * @return boolean True on success, false otherwise.
   */
  public function writeUpdateSupport($updateSupport) {
    return self::trySetValue(self::UPDATE_SUPPORT_KEY, $updateSupport);
  }

  /**
   * Gets the current ad tags.
   *
   * @return array|null The current tags, or null if none was set.
   */
  public function getTags() {
    return self::getValueOrDefault(self::TAGS_CONFIGURATION_KEY, null);
  }

  /**
   * Writes the ad tags.
   *
   * @param string $tags The ad tags to set.
   * @return boolean True on success, false otherwise.
   */
  public function writeTags($tags) {
    return self::trySetValue(self::TAGS_CONFIGURATION_KEY, $tags);
  }

  /**
   * Gets the key stored in the options. If this fails or has not been set then
   * the default is returned.
   *
   * @param $key The key to fetch.
   * @param $default The value to return if the fetch fails.
   * @return mixed The stored value or default
   */
  private function getValueOrDefault($key, $default) {
    $option = get_option(self::OPTIONS_NAME);
    if (!empty($option) && isset($option[$key])) {
      return $option[$key];
    } else {
      return $default;
    }
  }

  /**
   * Sets the value in the options. Fails if the options have not been created
   * or if they could not be written.
   *
   * @param $key The key to set.
   * @param $value The value to set.
   * @return boolean True on success, false otherwise.
   */
  private function trySetValue($key, $value) {
    $option = get_option(self::OPTIONS_NAME);
    if (empty($option)) {
      return false;
    }
    if (isset($option[$key]) && $option[$key] === $value) {
      return true;
    }
    $option[$key] = $value;
    return update_option(self::OPTIONS_NAME, $option);
  }
}
