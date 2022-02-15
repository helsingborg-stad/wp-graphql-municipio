<?php

namespace WPGraphQLMunicipio;

class App
{
    public function __construct()
    {
        if (!defined('DYNAMIC_CPT_OPTIONS_PREFIX')) {
            define('DYNAMIC_CPT_OPTIONS_PREFIX', 'options_avabile_dynamic_post_types_');
            define('DYNAMIC_CPT_OPTIONS_NAME_SUFFIX', '_post_type_name');
        }

        add_action('register_post_type_args', function ($args, $postType) {
            $dynamicPostTypes = $this->getDynamicPostTypes();
            if (\in_array($postType, $dynamicPostTypes)) {
                $args['show_in_graphql'] = true;
                $args['graphql_single_name'] = strtolower($args['labels']['singular_name']);
                $args['graphql_plural_name'] = strtolower($args['labels']['plural_name'] ?? $args['labels']['singular_name'] . 's');
            }


            return $args;
        }, 10, 2);
    }

    public function getDynamicPostTypes()
    {
        $dynamicPostTypes = [];

        $i = 0;

        while ($i !== -1) {
            $foundPostType = get_option(DYNAMIC_CPT_OPTIONS_PREFIX . $i . DYNAMIC_CPT_OPTIONS_NAME_SUFFIX, false);
            if ($foundPostType) {
                $dynamicPostTypes[] = strtolower($foundPostType);
                $i++;
            }

            $i = $foundPostType ? $i : -1;
        }

        return $dynamicPostTypes;
    }
}
