<?php

namespace WPGraphQLMunicipio;

class App
{
    public const DYNAMIC_CPT_OPTIONS_PREFIX = 'options_avabile_dynamic_post_types_';
    public const DYNAMIC_CPT_OPTIONS_SLUG_SUFFIX = '_slug';

    public const DYNAMIC_TAXONOMY_OPTIONS_PREFIX = 'options_avabile_dynamic_taxonomies_';
    public const DYNAMIC_TAXONOMY_OPTIONS_SLUG_SUFFIX = '_slug';

    public function __construct()
    {
        add_action('register_post_type_args', array($this, 'enableGraphQlForDynamicPostTypes'), 10, 2);
        add_action('register_taxonomy_args', array($this, 'enableGraphQlForDynamicTaxonomies'), 10, 2);
    }

    public function enableGraphQlForDynamicTaxonomies($args, $taxonomy)
    {
        $dynamicTaxonomies = $this->getDynamicTaxonomies();
        if (\in_array($taxonomy, $dynamicTaxonomies)) {
            $args['show_in_graphql'] = true;
            $args['graphql_single_name'] = $taxonomy;
            $args['graphql_plural_name'] = $taxonomy . 's';
        }


        return $args;
    }

    public function enableGraphQlForDynamicPostTypes($args, $postType)
    {
        $dynamicPostTypes = $this->getDynamicPostTypes();
        if (\in_array($postType, $dynamicPostTypes)) {
            $args['show_in_graphql'] = true;
            $args['graphql_single_name'] = strtolower($args['labels']['singular_name']);
            $args['graphql_plural_name'] = strtolower($args['labels']['plural_name'] ?? $args['labels']['singular_name'] . 's');
        }


        return $args;
    }

    public function getDynamicTaxonomies()
    {
        $dynamicTaxonomy = [];

        $i = 0;

        while ($i !== -1) {
            $foundTaxonomy = get_option(self::DYNAMIC_TAXONOMY_OPTIONS_PREFIX . $i . self::DYNAMIC_TAXONOMY_OPTIONS_SLUG_SUFFIX, false);
            if ($foundTaxonomy) {
                $dynamicTaxonomy[] = $foundTaxonomy;
                $i++;
            }

            $i = $foundTaxonomy ? $i : -1;
        }

        return $dynamicTaxonomy;
    }

    public function getDynamicPostTypes()
    {
        $dynamicPostTypes = [];

        $i = 0;

        while ($i !== -1) {
            $foundPostType = get_option(self::DYNAMIC_CPT_OPTIONS_PREFIX . $i . self::DYNAMIC_CPT_OPTIONS_SLUG_SUFFIX, false);
            if ($foundPostType) {
                $dynamicPostTypes[] = strtolower($foundPostType);
                $i++;
            }

            $i = $foundPostType ? $i : -1;
        }

        return $dynamicPostTypes;
    }
}
