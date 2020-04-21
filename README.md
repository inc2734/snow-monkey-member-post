# Snow Monkey Member Post

## Shortcode

### snow_monkey_member_post_login_form

Display the login form.

```
/**
 * @param  string  redirect_to  You can specify the URL to redirect when logging in.
 */
[snow_monkey_member_post_login_form redirect_to="(Optional)"]
```

### snow_monkey_member_post_register_form

Display the register form.

```
/**
 * @param  string  redirect_to  You can specify the URL to redirect when registered.
 */
[snow_monkey_member_post_register_form redirect_to="(Optional)"]
```

## Filter hooks

### snow_monkey_member_post_active_post_types
```
/**
 * You can customize the type of post that you can restrict.
 *
 * @param array active_post_types
 * @return array
 */
add_filter(
  'snow_monkey_member_post_active_post_types',
  function( $active_post_types ) {
    return $active_post_types;
  }
);
```

### snow_monkey_member_post_restriction_capability
```
/**
 * You can customize the permissions that can be restricted.
 *
 * @param string $capability
 * @return string
 */
add_filter(
  'snow_monkey_member_post_restriction_capability',
  function( $capability ) {
    return $capability;
  }
);
```

### snow_monkey_member_post_is_restricted
```
/**
 * You can customize whether the content is restricted or not.
 *
 * @param boolean $return
 * @param boolean $has_restriction_meta
 * @param WP_Post $post
 */
add_filter(
  'snow_monkey_member_post_is_restricted',
  function( $return, $has_restriction_meta, $post ) {
    return $return;
  },
  10,
  3
);
```

### snow_monkey_member_post_allowed_content_message
```
/**
 * You can customize the message displayed in the permitted content.
 *
 * @param string $message
 * @return string
 */
add_filter(
  'snow_monkey_member_post_allowed_content_message',
  function( $message, $post ) {
    return $message;
  },
  10,
  2
);
```

### snow_monkey_member_post_restricted_content_message
```
/**
 * You can customize the messages that appear on unauthorized content.
 *
 * @param string $message
 * @return string
 */
add_filter(
  'snow_monkey_member_post_restricted_content_message',
  function( $message, $post ) {
    return $message;
  },
  10,
  2
);
```

### snow_monkey_member_post_restricted_excerpt_message
```
/**
 * You can customize the messages that appear on excerpt of unauthorized content.
 *
 * @param string
 * @return staring
 */
add_filter(
  'snow_monkey_member_post_restricted_excerpt_message',
  function( $message, $post ) {
    return $message;
  },
	10,
	2
);
```

### snow_monkey_member_post_view_hierarchy
```
/**
 * You can customize the template directory.
 *
 * @param array $hierarchy
 * @param string $slug
 * @param string $name
 * @param array $vars
 * @return array
 */
add_filter(
  'snow_monkey_member_post_view_hierarchy',
  function( $hierarchy, $slug, $name, $vars ) {
    return $hierarchy;
  },
  10,
  4
);
```

### snow_monkey_member_post_view_render
```
/**
 * You can customize the template html.
 *
 * @param array $html
 * @param string $slug
 * @param string $name
 * @param array $vars
 * @return string
 */
add_filter(
  'snow_monkey_member_post_view_render',
  function( $html, $slug, $name, $vars ) {
    return $html;
  },
  10,
  4
);
```
