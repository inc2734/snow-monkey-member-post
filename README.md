# Snow Monkey Member Post

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
  function( $message ) {
    return $message;
  }
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
  function( $message ) {
    return $message;
  }
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
  function( $message ) {
    return $message;
  }
);
```

### snow_monkey_member_post_tepmlate_path
```
/**
 * You can customize the template to load.
 *
 * @param string $template_path
 * @param string $slug
 * @return string
 */
add_filter(
  'snow_monkey_member_post_tepmlate_path',
  function( $template_path, $slug ) {
    return $template_path;
  },
  10,
  2
);
