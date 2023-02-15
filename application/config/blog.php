<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['posts_per_page'] = '10';


$config['blog']['tables']['posts']               = 'blog_posts';
$config['blog']['tables']['categories']          = 'blog_categories';
$config['blog']['tables']['tags']                = 'blog_tags';
$config['blog']['tables']['tags_to_post']        = 'blog_tags_to_post';
$config['blog']['tables']['links']               = 'blog_links';
$config['blog']['tables']['comments']            = 'blog_comments';
$config['blog']['tables']['users']               = 'user';
$config['blog']['tables']['posts_to_categories'] = 'blog_posts_to_categories';
$config['blog']['tables']['settings']            = 'blog_settings';
$config['blog']['tables']['pages']               = 'blog_pages';
$config['blog']['tables']['navigation']          = 'blog_navigation';
$config['blog']['tables']['redirects']           = 'blog_redirects';
$config['blog']['tables']['templates']           = 'blog_templates';
$config['blog']['tables']['social']              = 'blog_social';
$config['blog']['tables']['notifications']       = 'blog_notifications';
$config['blog']['tables']['languages']           = 'blog_languages';

/*
 | Users table column and Group table column you want to join WITH.
 |
 | Joins from users.id
 | Joins from groups.id
 */
$config['join']['users']  = 'user_id';
$config['join']['groups'] = 'group_id';


