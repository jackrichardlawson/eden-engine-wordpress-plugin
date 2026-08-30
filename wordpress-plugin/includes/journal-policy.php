<?php
/**
 * Public editorial policy for legacy Eden Engine journal posts.
 *
 * Keeps historically useful articles available without allowing earlier vision
 * language to masquerade as current technical positioning.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'eden_engine_legacy_journal_redirects' ) ) {
    function eden_engine_legacy_journal_redirects(): array {
        return (array) apply_filters(
            'eden_engine_legacy_journal_redirects',
            array(
                'exploring-the-eden-engine-revolutionizing-food-production'   => 'what-is-the-eden-engine-and-why-the-world-needs-post-agricultural-food-systems',
                'introducing-the-eden-engine-revolutionizing-food-production' => 'what-is-the-eden-engine-and-why-the-world-needs-post-agricultural-food-systems',
                'how-closed-loop-food-systems-work-2'                         => 'how-closed-loop-food-systems-work',
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_redirect_legacy_journal_posts' ) ) {
    function eden_engine_redirect_legacy_journal_posts(): void {
        if ( ! is_singular( 'post' ) ) {
            return;
        }

        $post_id   = get_queried_object_id();
        $post_slug = (string) get_post_field( 'post_name', $post_id );
        $redirects = eden_engine_legacy_journal_redirects();

        if ( empty( $redirects[ $post_slug ] ) ) {
            return;
        }

        wp_safe_redirect( home_url( '/' . sanitize_title( (string) $redirects[ $post_slug ] ) . '/' ), 301 );
        exit;
    }
}

add_action( 'template_redirect', 'eden_engine_redirect_legacy_journal_posts', 5 );

if ( ! function_exists( 'eden_engine_journal_artifact_types' ) ) {
    function eden_engine_journal_artifact_types(): array {
        return (array) apply_filters(
            'eden_engine_journal_artifact_types',
            array(
                'dataset'            => 'Dataset',
                'experiment-record'  => 'Experiment record',
                'model-or-simulation' => 'Model or simulation',
                'analysis'           => 'Analysis',
                'protocol-or-method' => 'Protocol or method',
                'software-release'   => 'Software release',
                'technical-note'     => 'Technical note',
                'review'             => 'Review',
                'other'              => 'Other public artifact',
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_journal_artifact_statuses' ) ) {
    function eden_engine_journal_artifact_statuses(): array {
        return (array) apply_filters(
            'eden_engine_journal_artifact_statuses',
            array(
                'draft'        => 'Draft',
                'under-review' => 'Under review',
                'published'    => 'Published',
                'superseded'   => 'Superseded',
                'withdrawn'    => 'Withdrawn',
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_journal_claim_states' ) ) {
    function eden_engine_journal_claim_states(): array {
        return (array) apply_filters(
            'eden_engine_journal_claim_states',
            array(
                'external-precedent' => 'External precedent',
                'eden-modeled'       => 'Eden-modeled integration',
                'planned-validation' => 'Planned validation',
                'synthetic'          => 'Synthetic test data / not a physical result',
                'implemented-governance' => 'Implemented governance / internal decision protocol',
                'measured-unreviewed' => 'Measured / not independently reviewed',
                'measured-reviewed'  => 'Measured / independently reviewed',
                'qualified'          => 'Qualified for the stated use and boundary',
                'historical-vision'  => 'Historical vision / not current capability',
            )
        );
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_contract_choice' ) ) {
    function eden_engine_sanitize_journal_contract_choice( $value, array $choices ): string {
        $value = sanitize_key( (string) $value );

        return array_key_exists( $value, $choices ) ? $value : '';
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_artifact_type' ) ) {
    function eden_engine_sanitize_journal_artifact_type( $value ): string {
        return eden_engine_sanitize_journal_contract_choice( $value, eden_engine_journal_artifact_types() );
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_artifact_status' ) ) {
    function eden_engine_sanitize_journal_artifact_status( $value ): string {
        return eden_engine_sanitize_journal_contract_choice( $value, eden_engine_journal_artifact_statuses() );
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_claim_state' ) ) {
    function eden_engine_sanitize_journal_claim_state( $value ): string {
        return eden_engine_sanitize_journal_contract_choice( $value, eden_engine_journal_claim_states() );
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_contract_url' ) ) {
    function eden_engine_sanitize_journal_contract_url( $value ): string {
        $url = esc_url_raw( (string) $value, array( 'http', 'https' ) );

        if ( '' === $url ) {
            return '';
        }

        $scheme = strtolower( (string) wp_parse_url( $url, PHP_URL_SCHEME ) );
        $host   = (string) wp_parse_url( $url, PHP_URL_HOST );

        return in_array( $scheme, array( 'http', 'https' ), true ) && '' !== $host ? $url : '';
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_review_date' ) ) {
    function eden_engine_sanitize_journal_review_date( $value ): string {
        $value = trim( (string) $value );

        if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches ) ) {
            return '';
        }

        return checkdate( (int) $matches[2], (int) $matches[3], (int) $matches[1] ) ? $value : '';
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_string_list' ) ) {
    function eden_engine_sanitize_journal_string_list( $value ): array {
        if ( is_string( $value ) ) {
            $value = preg_split( '/\r\n|\r|\n/', $value );
        }

        if ( ! is_array( $value ) ) {
            return array();
        }

        $items = array();

        foreach ( $value as $item ) {
            if ( ! is_scalar( $item ) ) {
                continue;
            }

            $item = sanitize_text_field( (string) $item );

            if ( '' !== $item ) {
                $items[] = $item;
            }
        }

        return array_values( array_unique( $items ) );
    }
}

if ( ! function_exists( 'eden_engine_sanitize_journal_references' ) ) {
    function eden_engine_sanitize_journal_references( $value ): array {
        if ( ! is_array( $value ) ) {
            return array();
        }

        $references = array();

        foreach ( $value as $reference ) {
            if ( ! is_array( $reference ) ) {
                continue;
            }

            $label = isset( $reference['label'] ) ? sanitize_text_field( (string) $reference['label'] ) : '';
            $url   = isset( $reference['url'] ) ? eden_engine_sanitize_journal_contract_url( $reference['url'] ) : '';
            $type  = isset( $reference['type'] ) ? sanitize_text_field( (string) $reference['type'] ) : '';

            if ( '' === $label || '' === $url ) {
                continue;
            }

            $references[] = array(
                'label' => $label,
                'url'   => $url,
                'type'  => $type,
            );
        }

        return $references;
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_meta_definitions' ) ) {
    function eden_engine_journal_contract_meta_definitions(): array {
        $string_list_schema = array(
            'type'    => 'array',
            'items'   => array( 'type' => 'string' ),
            'default' => array(),
            'context' => array( 'view', 'edit' ),
        );
        $reference_schema   = array(
            'type'    => 'array',
            'items'   => array(
                'type'                 => 'object',
                'properties'           => array(
                    'label' => array( 'type' => 'string' ),
                    'url'   => array(
                        'type'   => 'string',
                        'format' => 'uri',
                    ),
                    'type'  => array( 'type' => 'string' ),
                ),
                'required'             => array( 'label', 'url' ),
                'additionalProperties' => false,
            ),
            'default' => array(),
            'context' => array( 'view', 'edit' ),
        );

        return array(
            'artifact_identifier' => array(
                'meta_key'          => '_eden_journal_artifact_identifier',
                'label'             => 'Artifact identifier',
                'description'       => 'Stable public identifier for the artifact supporting this post.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'artifact_url' => array(
                'meta_key'          => '_eden_journal_artifact_url',
                'label'             => 'Artifact link',
                'description'       => 'Public HTTP(S) link to the artifact.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'eden_engine_sanitize_journal_contract_url',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'format'  => 'uri',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'artifact_type' => array(
                'meta_key'          => '_eden_journal_artifact_type',
                'label'             => 'Artifact type',
                'description'       => 'Controlled artifact type.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'eden_engine_sanitize_journal_artifact_type',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'enum'    => array_keys( eden_engine_journal_artifact_types() ),
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'artifact_status' => array(
                'meta_key'          => '_eden_journal_artifact_status',
                'label'             => 'Artifact status',
                'description'       => 'Publication and review status of the linked artifact.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'eden_engine_sanitize_journal_artifact_status',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'enum'    => array_keys( eden_engine_journal_artifact_statuses() ),
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'claim_state' => array(
                'meta_key'          => '_eden_journal_claim_state',
                'label'             => 'Claim state',
                'description'       => 'Evidence state for the narrow claim made by this post.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'eden_engine_sanitize_journal_claim_state',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'enum'    => array_keys( eden_engine_journal_claim_states() ),
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'what_changed' => array(
                'meta_key'          => '_eden_journal_what_changed',
                'label'             => 'What changed',
                'description'       => 'Concrete change represented by this publication.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'narrow_support' => array(
                'meta_key'          => '_eden_journal_narrow_support',
                'label'             => 'What the artifact supports',
                'description'       => 'Smallest defensible interpretation supported by the artifact.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'excluded_inferences' => array(
                'meta_key'          => '_eden_journal_excluded_inferences',
                'label'             => 'Excluded inferences',
                'description'       => 'Claims readers must not infer from the artifact.',
                'type'              => 'array',
                'default'           => array(),
                'sanitize_callback' => 'eden_engine_sanitize_journal_string_list',
                'rest_schema'       => $string_list_schema,
            ),
            'evidence_references' => array(
                'meta_key'          => '_eden_journal_evidence_references',
                'label'             => 'Evidence and references',
                'description'       => 'Public evidence or primary references with labels and HTTP(S) links.',
                'type'              => 'array',
                'default'           => array(),
                'sanitize_callback' => 'eden_engine_sanitize_journal_references',
                'rest_schema'       => $reference_schema,
            ),
            'next_gate' => array(
                'meta_key'          => '_eden_journal_next_gate',
                'label'             => 'Next evidence gate',
                'description'       => 'Smallest next validation step that would reduce uncertainty.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'review_date' => array(
                'meta_key'          => '_eden_journal_review_date',
                'label'             => 'Technical review date',
                'description'       => 'Latest technical review date in YYYY-MM-DD format.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'eden_engine_sanitize_journal_review_date',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'pattern' => '^\d{4}-\d{2}-\d{2}$',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'eden_interpretation' => array(
                'meta_key'          => '_eden_journal_eden_interpretation',
                'label'             => 'Eden interpretation',
                'description'       => 'Eden-specific interpretation separated from the underlying evidence.',
                'type'              => 'string',
                'default'           => '',
                'sanitize_callback' => 'sanitize_textarea_field',
                'rest_schema'       => array(
                    'type'    => 'string',
                    'context' => array( 'view', 'edit' ),
                ),
            ),
            'unknowns' => array(
                'meta_key'          => '_eden_journal_unknowns',
                'label'             => 'Unknowns and risks',
                'description'       => 'Remaining unknowns, risks, or unresolved assumptions.',
                'type'              => 'array',
                'default'           => array(),
                'sanitize_callback' => 'eden_engine_sanitize_journal_string_list',
                'rest_schema'       => $string_list_schema,
            ),
        );
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_meta_auth' ) ) {
    function eden_engine_journal_contract_meta_auth( $allowed, $meta_key, $post_id ): bool {
        unset( $allowed, $meta_key );

        return $post_id ? current_user_can( 'edit_post', (int) $post_id ) : current_user_can( 'edit_posts' );
    }
}

if ( ! function_exists( 'eden_engine_register_journal_contract_meta' ) ) {
    function eden_engine_register_journal_contract_meta(): void {
        foreach ( eden_engine_journal_contract_meta_definitions() as $definition ) {
            register_post_meta(
                'post',
                (string) $definition['meta_key'],
                array(
                    'description'       => (string) $definition['description'],
                    'single'            => true,
                    'type'              => (string) $definition['type'],
                    'sanitize_callback' => (string) $definition['sanitize_callback'],
                    'auth_callback'     => 'eden_engine_journal_contract_meta_auth',
                    'show_in_rest'      => array(
                        'schema' => $definition['rest_schema'],
                    ),
                )
            );
        }
    }
}

add_action( 'init', 'eden_engine_register_journal_contract_meta', 20 );

if ( ! function_exists( 'eden_engine_journal_contract_required_fields' ) ) {
    function eden_engine_journal_contract_required_fields( int $post_id = 0 ): array {
        unset( $post_id );

        return array(
            'artifact_identifier',
            'artifact_url',
            'artifact_type',
            'artifact_status',
            'claim_state',
            'what_changed',
            'narrow_support',
            'excluded_inferences',
            'evidence_references',
            'next_gate',
            'review_date',
            'eden_interpretation',
            'unknowns',
        );
    }
}

if ( ! function_exists( 'eden_engine_normalize_journal_contract_fields' ) ) {
    function eden_engine_normalize_journal_contract_fields( array $fields ): array {
        $normalized = array();

        foreach ( eden_engine_journal_contract_meta_definitions() as $field_name => $definition ) {
            $value     = array_key_exists( $field_name, $fields ) ? $fields[ $field_name ] : $definition['default'];
            $sanitizer = (string) $definition['sanitize_callback'];

            $normalized[ $field_name ] = is_callable( $sanitizer )
                ? call_user_func( $sanitizer, $value )
                : $value;
        }

        return $normalized;
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_value_is_present' ) ) {
    function eden_engine_journal_contract_value_is_present( $value ): bool {
        if ( is_array( $value ) ) {
            return ! empty( $value );
        }

        return '' !== trim( (string) $value );
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_choice_label' ) ) {
    function eden_engine_journal_contract_choice_label( string $field_name, string $value ): string {
        $choices = array();

        if ( 'artifact_type' === $field_name ) {
            $choices = eden_engine_journal_artifact_types();
        } elseif ( 'artifact_status' === $field_name ) {
            $choices = eden_engine_journal_artifact_statuses();
        } elseif ( 'claim_state' === $field_name ) {
            $choices = eden_engine_journal_claim_states();
        }

        return isset( $choices[ $value ] ) ? (string) $choices[ $value ] : '';
    }
}

if ( ! function_exists( 'eden_engine_journal_publication_contract_for_post' ) ) {
    function eden_engine_journal_publication_contract_for_post( ?int $post_id = null ): array {
        $post_id     = $post_id ?: get_queried_object_id();
        $definitions = eden_engine_journal_contract_meta_definitions();
        $fields      = array();

        foreach ( $definitions as $field_name => $definition ) {
            $fields[ $field_name ] = $post_id
                ? get_post_meta( $post_id, (string) $definition['meta_key'], true )
                : $definition['default'];
        }

        /**
         * Filters publication-contract source fields before normalization.
         *
         * This permits an existing editorial store to supply the same fixed
         * contract without changing the completeness or artifact-backed rule.
         */
        $filtered_fields = apply_filters( 'eden_engine_journal_contract_fields', $fields, $post_id );

        if ( is_array( $filtered_fields ) ) {
            $fields = $filtered_fields;
        }

        $fields          = eden_engine_normalize_journal_contract_fields( $fields );
        $has_contract    = false;
        $missing_fields  = array();
        $required_fields = eden_engine_journal_contract_required_fields( (int) $post_id );

        foreach ( $fields as $value ) {
            if ( eden_engine_journal_contract_value_is_present( $value ) ) {
                $has_contract = true;
                break;
            }
        }

        foreach ( $required_fields as $field_name ) {
            if (
                ! array_key_exists( $field_name, $fields ) ||
                ! eden_engine_journal_contract_value_is_present( $fields[ $field_name ] )
            ) {
                $missing_fields[] = $field_name;
            }
        }

        if ( $post_id && ! empty( $fields['artifact_url'] ) && function_exists( 'get_permalink' ) ) {
            $artifact_url = untrailingslashit( strtolower( (string) strtok( (string) $fields['artifact_url'], '?#' ) ) );
            $post_url     = untrailingslashit( strtolower( (string) strtok( (string) get_permalink( $post_id ), '?#' ) ) );

            if ( '' !== $post_url && $artifact_url === $post_url && ! in_array( 'artifact_url', $missing_fields, true ) ) {
                $missing_fields[] = 'artifact_url';
            }
        }

        $artifact_backed = $has_contract && empty( $missing_fields ) && 'published' === $fields['artifact_status'];
        $contract_status = 'not-declared';

        if ( $has_contract && ! empty( $missing_fields ) ) {
            $contract_status = 'incomplete';
        } elseif ( $artifact_backed ) {
            $contract_status = 'artifact-backed';
        } elseif ( $has_contract ) {
            $contract_status = 'not-published';
        }

        return array_merge(
            array(
                'version'                 => '1.0',
                'post_id'                 => (int) $post_id,
                'has_contract'            => $has_contract,
                'artifact_backed'         => $artifact_backed,
                'contract_status'         => $contract_status,
                'missing_required_fields' => $missing_fields,
                'artifact_type_label'     => eden_engine_journal_contract_choice_label( 'artifact_type', (string) $fields['artifact_type'] ),
                'artifact_status_label'   => eden_engine_journal_contract_choice_label( 'artifact_status', (string) $fields['artifact_status'] ),
                'claim_state_label'       => eden_engine_journal_contract_choice_label( 'claim_state', (string) $fields['claim_state'] ),
            ),
            $fields
        );
    }
}

if ( ! function_exists( 'eden_engine_is_artifact_backed_post' ) ) {
    function eden_engine_is_artifact_backed_post( ?int $post_id = null ): bool {
        $contract = eden_engine_journal_publication_contract_for_post( $post_id );

        return ! empty( $contract['artifact_backed'] );
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_rest_schema' ) ) {
    function eden_engine_journal_contract_rest_schema(): array {
        $properties = array(
            'version' => array(
                'type'     => 'string',
                'readonly' => true,
            ),
            'post_id' => array(
                'type'     => 'integer',
                'readonly' => true,
            ),
            'has_contract' => array(
                'type'     => 'boolean',
                'readonly' => true,
            ),
            'artifact_backed' => array(
                'type'     => 'boolean',
                'readonly' => true,
            ),
            'contract_status' => array(
                'type'     => 'string',
                'enum'     => array( 'not-declared', 'incomplete', 'not-published', 'artifact-backed' ),
                'readonly' => true,
            ),
            'missing_required_fields' => array(
                'type'     => 'array',
                'items'    => array( 'type' => 'string' ),
                'readonly' => true,
            ),
            'artifact_type_label' => array(
                'type'     => 'string',
                'readonly' => true,
            ),
            'artifact_status_label' => array(
                'type'     => 'string',
                'readonly' => true,
            ),
            'claim_state_label' => array(
                'type'     => 'string',
                'readonly' => true,
            ),
            'extensions' => array(
                'type'                 => 'object',
                'additionalProperties' => true,
                'readonly'             => true,
            ),
        );

        foreach ( eden_engine_journal_contract_meta_definitions() as $field_name => $definition ) {
            $properties[ $field_name ]             = $definition['rest_schema'];
            $properties[ $field_name ]['readonly'] = true;
        }

        return array(
            'description'          => 'Validated Eden Journal publication contract. Null means no contract has been declared.',
            'type'                 => array( 'object', 'null' ),
            'properties'           => $properties,
            'additionalProperties' => false,
            'context'              => array( 'view', 'edit' ),
            'readonly'             => true,
        );
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_rest_value' ) ) {
    function eden_engine_journal_contract_rest_value( $prepared_post ) {
        $post_id = is_array( $prepared_post ) && isset( $prepared_post['id'] )
            ? (int) $prepared_post['id']
            : 0;

        if ( ! $post_id && is_object( $prepared_post ) && isset( $prepared_post->ID ) ) {
            $post_id = (int) $prepared_post->ID;
        }

        $contract = eden_engine_journal_publication_contract_for_post( $post_id );

        if ( empty( $contract['has_contract'] ) ) {
            return null;
        }

        $extensions = apply_filters(
            'eden_engine_journal_contract_rest_extensions',
            array(),
            $post_id,
            $contract
        );

        if ( is_array( $extensions ) && ! empty( $extensions ) ) {
            $contract['extensions'] = $extensions;
        }

        // Omit absent values so incomplete contracts still match the public
        // response schema without weakening the write schema for valid values.
        foreach ( array_keys( eden_engine_journal_contract_meta_definitions() ) as $field_name ) {
            if ( ! eden_engine_journal_contract_value_is_present( $contract[ $field_name ] ?? '' ) ) {
                unset( $contract[ $field_name ] );
            }
        }

        return $contract;
    }
}

if ( ! function_exists( 'eden_engine_register_journal_contract_rest_field' ) ) {
    function eden_engine_register_journal_contract_rest_field(): void {
        register_rest_field(
            'post',
            'eden_journal_contract',
            array(
                'get_callback' => 'eden_engine_journal_contract_rest_value',
                'schema'       => eden_engine_journal_contract_rest_schema(),
            )
        );
    }
}

add_action( 'rest_api_init', 'eden_engine_register_journal_contract_rest_field' );

if ( ! function_exists( 'eden_engine_journal_contract_status_label' ) ) {
    function eden_engine_journal_contract_status_label( array $contract ): string {
        if ( ! empty( $contract['artifact_backed'] ) ) {
            return 'Artifact-backed publication';
        }

        if ( 'incomplete' === ( $contract['contract_status'] ?? '' ) ) {
            return 'Publication contract incomplete — not artifact-backed';
        }

        if ( ! empty( $contract['artifact_status_label'] ) ) {
            return (string) $contract['artifact_status_label'] . ' artifact — not artifact-backed';
        }

        return 'Not labeled artifact-backed';
    }
}

if ( ! function_exists( 'eden_engine_journal_artifact_badge_html' ) ) {
    function eden_engine_journal_artifact_badge_html( ?int $post_id = null, ?array $contract = null ): string {
        if ( null === $contract ) {
            $contract = eden_engine_journal_publication_contract_for_post( $post_id );
        }

        if ( empty( $contract['artifact_backed'] ) ) {
            return '';
        }

        $claim_state = ! empty( $contract['claim_state_label'] )
            ? '; claim state: ' . (string) $contract['claim_state_label']
            : '';

        return '<span class="status-badge status-badge--green" aria-label="Artifact-backed publication' . esc_attr( $claim_state ) . '">Artifact-backed</span>';
    }
}

if ( ! function_exists( 'eden_engine_journal_contract_list_html' ) ) {
    function eden_engine_journal_contract_list_html( array $items ): string {
        $html = '<ul>';

        foreach ( $items as $item ) {
            $html .= '<li>' . esc_html( (string) $item ) . '</li>';
        }

        return $html . '</ul>';
    }
}

if ( ! function_exists( 'eden_engine_journal_publication_contract_html' ) ) {
    function eden_engine_journal_publication_contract_html( ?int $post_id = null, ?array $contract = null ): string {
        if ( null === $contract ) {
            $contract = eden_engine_journal_publication_contract_for_post( $post_id );
        }

        if ( empty( $contract['has_contract'] ) ) {
            return '';
        }

        $heading_id   = 'eden-journal-publication-contract-' . absint( $contract['post_id'] );
        $definitions  = eden_engine_journal_contract_meta_definitions();
        $status_label = eden_engine_journal_contract_status_label( $contract );
        $html         = '<section class="eden-journal-correction eden-journal-publication-contract" aria-labelledby="' . esc_attr( $heading_id ) . '">';
        $html        .= '<p class="eden-journal-eyebrow">Public evidence contract</p>';
        $html        .= '<h2 id="' . esc_attr( $heading_id ) . '">' . esc_html( $status_label ) . '</h2>';
        $html        .= '<p>An artifact link establishes traceability, not a broader production, safety, environmental, economic, or deployment claim. The claim state and exclusions below define the public boundary.</p>';

        if ( ! empty( $contract['missing_required_fields'] ) ) {
            $missing_labels = array();

            foreach ( $contract['missing_required_fields'] as $field_name ) {
                if ( isset( $definitions[ $field_name ]['label'] ) ) {
                    $missing_labels[] = (string) $definitions[ $field_name ]['label'];
                }
            }

            if ( ! empty( $missing_labels ) ) {
                $html .= '<h3>Required before an artifact-backed label</h3>';
                $html .= eden_engine_journal_contract_list_html( $missing_labels );
            }
        }

        $summary_html = '';

        if ( ! empty( $contract['artifact_identifier'] ) ) {
            $artifact_value = esc_html( (string) $contract['artifact_identifier'] );

            if ( ! empty( $contract['artifact_url'] ) ) {
                $artifact_value = '<a href="' . esc_url( (string) $contract['artifact_url'] ) . '">' . $artifact_value . '</a>';
            }

            $summary_html .= '<div><dt>Artifact</dt><dd>' . $artifact_value . '</dd></div>';
        }

        if ( ! empty( $contract['artifact_type_label'] ) ) {
            $summary_html .= '<div><dt>Artifact type</dt><dd>' . esc_html( (string) $contract['artifact_type_label'] ) . '</dd></div>';
        }

        if ( ! empty( $contract['artifact_status_label'] ) ) {
            $summary_html .= '<div><dt>Artifact status</dt><dd>' . esc_html( (string) $contract['artifact_status_label'] ) . '</dd></div>';
        }

        if ( ! empty( $contract['claim_state_label'] ) ) {
            $summary_html .= '<div><dt>Claim state</dt><dd>' . esc_html( (string) $contract['claim_state_label'] ) . '</dd></div>';
        }

        if ( ! empty( $contract['review_date'] ) ) {
            $summary_html .= '<div><dt>Technical review date</dt><dd><time datetime="' . esc_attr( (string) $contract['review_date'] ) . '">' . esc_html( (string) $contract['review_date'] ) . '</time></dd></div>';
        }

        if ( '' !== $summary_html ) {
            $html .= '<dl class="eden-journal-publication-contract__summary">' . $summary_html . '</dl>';
        }

        $narrative_fields = array(
            'what_changed'        => 'What changed',
            'narrow_support'      => 'What the artifact narrowly supports',
            'eden_interpretation' => 'Eden interpretation',
            'next_gate'           => 'Next evidence gate',
        );

        foreach ( $narrative_fields as $field_name => $heading ) {
            if ( ! empty( $contract[ $field_name ] ) ) {
                $html .= '<h3>' . esc_html( $heading ) . '</h3>';
                $html .= '<p>' . nl2br( esc_html( (string) $contract[ $field_name ] ) ) . '</p>';
            }
        }

        if ( ! empty( $contract['excluded_inferences'] ) ) {
            $html .= '<h3>Excluded inferences</h3>';
            $html .= eden_engine_journal_contract_list_html( (array) $contract['excluded_inferences'] );
        }

        if ( ! empty( $contract['unknowns'] ) ) {
            $html .= '<h3>Unknowns and risks</h3>';
            $html .= eden_engine_journal_contract_list_html( (array) $contract['unknowns'] );
        }

        if ( ! empty( $contract['evidence_references'] ) ) {
            $html .= '<h3>Evidence and references</h3><ol>';

            foreach ( (array) $contract['evidence_references'] as $reference ) {
                $reference_type = ! empty( $reference['type'] )
                    ? ' <span>(' . esc_html( (string) $reference['type'] ) . ')</span>'
                    : '';
                $html          .= '<li><a href="' . esc_url( (string) $reference['url'] ) . '">' . esc_html( (string) $reference['label'] ) . '</a>' . $reference_type . '</li>';
            }

            $html .= '</ol>';
        }

        return $html . '</section>';
    }
}

if ( ! function_exists( 'eden_engine_reviewed_journal_policy' ) ) {
    function eden_engine_reviewed_journal_policy(): array {
        return array(
            'what-exp-bal-gas-must-measure-before-it-counts-as-eden-evidence' => array(
                'title'       => 'What EXP-BAL-GAS Must Measure Before It Counts as Eden Evidence',
                'description' => 'The reviewed EXP-BAL-GAS readiness package defines the gas, biomass, composition, control, uncertainty, and HOLD conditions required before a run could update an Eden parameter.',
                'claim_class' => 'Planned validation / physical run not executed',
            ),
            'why-synthetic-data-can-test-edens-pipeline-but-cannot-become-measured-eden-evidence' => array(
                'title'       => 'Why Synthetic Data Can Test Eden’s Pipeline but Cannot Become Measured Eden Evidence',
                'description' => 'Synthetic fixtures test ingestion, conservation, provenance, replay, model wiring, and failure behavior while Eden’s promotion gate keeps them out of measured evidence.',
                'claim_class' => 'Synthetic test data / not a physical result',
            ),
            'how-edens-current-evidence-gated-decision-protocol-works' => array(
                'title'       => 'How Eden’s Current Evidence-Gated Decision Protocol Works',
                'description' => 'Eden separates observation qualification, measured-parameter promotion, model and uncertainty updates, and campaign reprioritization with explicit repeat, reroute, and HOLD conditions.',
                'claim_class' => 'Implemented governance / planned validation',
            ),
            'what-carbon-negative-food-actually-means' => array(
                'title'       => 'What “Carbon-Negative Food” Would Need to Demonstrate',
                'description' => 'A carbon-negative food claim requires a named boundary, baseline, carbon origin, energy supply, coproduct treatment, product fate, and evidence for any durable storage or avoided emissions.',
                'claim_class' => 'Carbon claim audit / evidence required',
            ),
            'why-co2-to-sugar-is-the-first-step-toward-post-agricultural-humanity' => array(
                'title'       => 'Why Carbohydrate Synthesis Remains a Parallel Breakthrough Program',
                'description' => 'A historical sugar-first vision reframed around Eden’s current structure: Phase 1A microbial-biomass validation and a separate, higher-risk Phase 1B carbohydrate research program.',
                'claim_class' => 'Historical vision / parallel research target',
            ),
            'the-eden-engine-phase-1-roadmap-from-co2-to-sugar' => array(
                'title'       => 'Eden Engine Phase 1A / 1B Roadmap: Biomass Validation and Parallel Carbohydrate Research',
                'description' => 'The current roadmap separates a bounded microbial-biomass validation program from parallel carbohydrate route selection and analytical-feasibility work.',
                'claim_class' => 'Planned validation / no result implied',
            ),
            'is-co2-derived-sugar-safe-to-eat' => array(
                'title'       => 'What Would Need to Be Proven Before CO₂-Derived Sugar Could Be Considered Safe?',
                'description' => 'A food-safety determination requires a defined product, process, impurity profile, intended use, specification, regulatory route, and Eden-specific evidence.',
                'claim_class' => 'Safety question / not yet demonstrated',
            ),
            'what-happens-to-global-food-security-when-sugar-becomes-unlimited' => array(
                'title'       => 'What Global Food Security Would Need From a Validated Carbon-to-Carbohydrate Pathway',
                'description' => 'A systems-level review of the evidence, infrastructure, safety, cost, and distribution conditions required before a carbon-to-carbohydrate pathway could affect food security.',
                'claim_class' => 'Future application / conditional scenario',
            ),
            'the-case-for-co2-to-sugar-cleaner-cheaper-local-and-limitless' => array(
                'title'       => 'What a CO₂-to-Carbohydrate Pathway Would Need to Prove',
                'description' => 'A claim-discipline checklist for evaluating purity, energy, cost, locality, carbon accounting, and scale in a proposed CO₂-to-carbohydrate pathway.',
                'claim_class' => 'Research hypothesis / unvalidated pathway',
            ),
            'how-co2-to-sugar-reduces-land-use-water-use-and-emissions' => array(
                'title'       => 'How to Evaluate Land, Water, and Emissions Claims for CO₂-to-Carbohydrate Pathways',
                'description' => 'A boundary-first framework for comparing land, water, energy, carbon source, and emissions against a named conventional ingredient pathway.',
                'claim_class' => 'Comparative model question / evidence required',
            ),
            'why-co%e2%82%82-is-the-most-abundant-untapped-food-resource-on-earth' => array(
                'title'       => 'Why Carbon Availability Is Only One Input to Food-System Conversion',
                'description' => 'Carbon availability alone does not establish a viable food pathway; source quality, energy, conversion, recovery, safety, cost, and validation remain decisive.',
                'claim_class' => 'Research framing / not a production claim',
            ),
            'how-co2-to-sugar-could-strengthen-disaster-relief-and-emergency-response' => array(
                'title'       => 'What a Validated Carbon-to-Carbohydrate Pathway Could Mean for Emergency Food Systems',
                'description' => 'A conditional resilience scenario describing the validation, logistics, safety, energy, and operating evidence needed before emergency-system use.',
                'claim_class' => 'Future application / conditional scenario',
            ),
            'what-abundant-clean-sugar-means-for-everyday-people' => array(
                'title'       => 'What a Validated Carbon-to-Carbohydrate Ingredient Could Mean for Consumers',
                'description' => 'A conditional consumer scenario that separates a future ingredient vision from current Eden capability, safety status, economics, and measured evidence.',
                'claim_class' => 'Future application / conditional scenario',
            ),
        );
    }
}

if ( ! function_exists( 'eden_engine_reviewed_journal_titles' ) ) {
    function eden_engine_reviewed_journal_titles(): array {
        $titles = array();

        foreach ( eden_engine_reviewed_journal_policy() as $slug => $policy ) {
            $titles[ $slug ] = (string) $policy['title'];
        }

        return $titles;
    }
}

if ( ! function_exists( 'eden_engine_reviewed_journal_policy_for_post' ) ) {
    function eden_engine_reviewed_journal_policy_for_post( ?int $post_id = null ): array {
        $post_id = $post_id ?: get_queried_object_id();

        if ( ! $post_id || 'post' !== get_post_type( $post_id ) ) {
            return array();
        }

        $post_slug = (string) get_post_field( 'post_name', $post_id );
        $policy    = eden_engine_reviewed_journal_policy();

        return isset( $policy[ $post_slug ] ) ? (array) $policy[ $post_slug ] : array();
    }
}

if ( ! function_exists( 'eden_engine_filter_reviewed_journal_title' ) ) {
    function eden_engine_filter_reviewed_journal_title( string $title, int $post_id ): string {
        if ( 'post' !== get_post_type( $post_id ) ) {
            return $title;
        }

        $post_slug = (string) get_post_field( 'post_name', $post_id );
        $titles    = eden_engine_reviewed_journal_titles();

        return isset( $titles[ $post_slug ] ) ? (string) $titles[ $post_slug ] : $title;
    }
}

add_filter( 'the_title', 'eden_engine_filter_reviewed_journal_title', 20, 2 );

if ( ! function_exists( 'eden_engine_filter_reviewed_document_title' ) ) {
    function eden_engine_filter_reviewed_document_title( array $parts ): array {
        if ( ! is_singular( 'post' ) ) {
            return $parts;
        }

        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( ! empty( $policy['title'] ) ) {
            $parts['title'] = (string) $policy['title'];
        }

        return $parts;
    }
}

add_filter( 'document_title_parts', 'eden_engine_filter_reviewed_document_title', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_seo_title' ) ) {
    function eden_engine_filter_reviewed_seo_title( string $title ): string {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        return ! empty( $policy['title'] ) ? (string) $policy['title'] . ' | ' . get_bloginfo( 'name' ) : $title;
    }
}

if ( ! function_exists( 'eden_engine_filter_reviewed_seo_description' ) ) {
    function eden_engine_filter_reviewed_seo_description( string $description ): string {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        return ! empty( $policy['description'] ) ? (string) $policy['description'] : $description;
    }
}

add_filter( 'wpseo_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'wpseo_opengraph_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'wpseo_twitter_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'rank_math/frontend/title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'rank_math/opengraph/facebook/og_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'rank_math/opengraph/twitter/twitter_title', 'eden_engine_filter_reviewed_seo_title', 40 );
add_filter( 'wpseo_metadesc', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'wpseo_opengraph_desc', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'wpseo_twitter_description', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'rank_math/frontend/description', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'rank_math/opengraph/facebook/og_description', 'eden_engine_filter_reviewed_seo_description', 40 );
add_filter( 'rank_math/opengraph/twitter/twitter_description', 'eden_engine_filter_reviewed_seo_description', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_social_image' ) ) {
    function eden_engine_filter_reviewed_social_image( string $image ): string {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( empty( $policy ) || ! function_exists( 'eden_engine_post_image_url' ) ) {
            return $image;
        }

        return eden_engine_post_image_url( get_queried_object_id(), 'large' );
    }
}

add_filter( 'wpseo_opengraph_image', 'eden_engine_filter_reviewed_social_image', 40 );
add_filter( 'wpseo_twitter_image', 'eden_engine_filter_reviewed_social_image', 40 );
add_filter( 'rank_math/opengraph/facebook/og_image', 'eden_engine_filter_reviewed_social_image', 40 );
add_filter( 'rank_math/opengraph/twitter/twitter_image', 'eden_engine_filter_reviewed_social_image', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_yoast_article_schema' ) ) {
    function eden_engine_filter_reviewed_yoast_article_schema( array $data ): array {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( ! empty( $policy['title'] ) ) {
            $data['headline'] = (string) $policy['title'];
        }

        if ( ! empty( $policy['description'] ) ) {
            $data['description'] = (string) $policy['description'];
        }

        return $data;
    }
}

add_filter( 'wpseo_schema_article', 'eden_engine_filter_reviewed_yoast_article_schema', 40 );

if ( ! function_exists( 'eden_engine_filter_reviewed_rank_math_schema' ) ) {
    function eden_engine_filter_reviewed_rank_math_schema( array $data ): array {
        $policy = eden_engine_reviewed_journal_policy_for_post();

        if ( empty( $policy ) ) {
            return $data;
        }

        foreach ( $data as &$schema_piece ) {
            if ( ! is_array( $schema_piece ) || empty( $schema_piece['@type'] ) ) {
                continue;
            }

            $schema_types = is_array( $schema_piece['@type'] )
                ? $schema_piece['@type']
                : array( $schema_piece['@type'] );
            $is_article   = (bool) array_intersect(
                array( 'Article', 'BlogPosting', 'NewsArticle' ),
                array_map( 'strval', $schema_types )
            );

            if ( ! $is_article ) {
                continue;
            }

            $schema_piece['headline']    = (string) $policy['title'];
            $schema_piece['description'] = (string) $policy['description'];
        }

        unset( $schema_piece );

        return $data;
    }
}

add_filter( 'rank_math/json_ld', 'eden_engine_filter_reviewed_rank_math_schema', 40 );

if ( ! function_exists( 'eden_engine_reviewed_journal_head_metadata' ) ) {
    function eden_engine_reviewed_journal_head_metadata(): void {
        if ( ! is_singular( 'post' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }

        $post_id = get_queried_object_id();
        $policy  = eden_engine_reviewed_journal_policy_for_post( $post_id );

        if ( empty( $policy ) ) {
            return;
        }

        $title       = (string) $policy['title'];
        $description = (string) $policy['description'];
        $permalink   = get_permalink( $post_id );
        $schema      = array(
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => $title,
            'description'      => $description,
            'datePublished'    => get_the_date( DATE_W3C, $post_id ),
            'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
            'mainEntityOfPage' => $permalink,
            'publisher'        => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url( '/' ),
            ),
        );

        $image_url = function_exists( 'eden_engine_post_image_url' )
            ? eden_engine_post_image_url( $post_id, 'large' )
            : '';

        if ( $image_url ) {
            $schema['image'] = $image_url;
        }

        echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $permalink ) . '" />' . "\n";
        if ( $image_url ) {
            echo '<meta property="og:image" content="' . esc_url( $image_url ) . '" />' . "\n";
        }
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";
        if ( $image_url ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '" />' . "\n";
        }
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}

add_action( 'wp_head', 'eden_engine_reviewed_journal_head_metadata', 5 );

if ( ! function_exists( 'eden_engine_journal_index_metadata' ) ) {
    function eden_engine_journal_index_metadata(): array {
        if ( ! is_home() ) {
            return array();
        }

        $posts_page_id = (int) get_option( 'page_for_posts', 0 );
        $url           = $posts_page_id > 0 ? get_permalink( $posts_page_id ) : home_url( '/journal/' );

        return array(
            'title'       => 'Research Journal and Artifact-Backed Build Log',
            'description' => 'Reviewed Eden Engine research notes, public evidence contracts, claim boundaries, artifact receipts, and the next evidence gate for each technical update.',
            'url'         => $url ?: home_url( '/journal/' ),
            'image'       => EDEN_ENGINE_PLUGIN_URL . 'assets/images/eden-engine/pages/journal/field-notes-v2.webp',
        );
    }
}

if ( ! function_exists( 'eden_engine_filter_journal_index_document_title' ) ) {
    function eden_engine_filter_journal_index_document_title( array $parts ): array {
        $metadata = eden_engine_journal_index_metadata();

        if ( ! empty( $metadata['title'] ) ) {
            $parts['title'] = (string) $metadata['title'];
        }

        return $parts;
    }
}

add_filter( 'document_title_parts', 'eden_engine_filter_journal_index_document_title', 35 );

if ( ! function_exists( 'eden_engine_filter_journal_index_seo_title' ) ) {
    function eden_engine_filter_journal_index_seo_title( string $title ): string {
        $metadata = eden_engine_journal_index_metadata();

        return ! empty( $metadata['title'] ) ? (string) $metadata['title'] . ' | ' . get_bloginfo( 'name' ) : $title;
    }
}

if ( ! function_exists( 'eden_engine_filter_journal_index_seo_description' ) ) {
    function eden_engine_filter_journal_index_seo_description( string $description ): string {
        $metadata = eden_engine_journal_index_metadata();

        return ! empty( $metadata['description'] ) ? (string) $metadata['description'] : $description;
    }
}

if ( ! function_exists( 'eden_engine_filter_journal_index_social_image' ) ) {
    function eden_engine_filter_journal_index_social_image( string $image ): string {
        $metadata = eden_engine_journal_index_metadata();

        return ! empty( $metadata['image'] ) ? (string) $metadata['image'] : $image;
    }
}

add_filter( 'wpseo_title', 'eden_engine_filter_journal_index_seo_title', 35 );
add_filter( 'wpseo_opengraph_title', 'eden_engine_filter_journal_index_seo_title', 35 );
add_filter( 'wpseo_twitter_title', 'eden_engine_filter_journal_index_seo_title', 35 );
add_filter( 'rank_math/frontend/title', 'eden_engine_filter_journal_index_seo_title', 35 );
add_filter( 'rank_math/opengraph/facebook/og_title', 'eden_engine_filter_journal_index_seo_title', 35 );
add_filter( 'rank_math/opengraph/twitter/twitter_title', 'eden_engine_filter_journal_index_seo_title', 35 );
add_filter( 'wpseo_metadesc', 'eden_engine_filter_journal_index_seo_description', 35 );
add_filter( 'wpseo_opengraph_desc', 'eden_engine_filter_journal_index_seo_description', 35 );
add_filter( 'wpseo_twitter_description', 'eden_engine_filter_journal_index_seo_description', 35 );
add_filter( 'rank_math/frontend/description', 'eden_engine_filter_journal_index_seo_description', 35 );
add_filter( 'rank_math/opengraph/facebook/og_description', 'eden_engine_filter_journal_index_seo_description', 35 );
add_filter( 'rank_math/opengraph/twitter/twitter_description', 'eden_engine_filter_journal_index_seo_description', 35 );
add_filter( 'wpseo_opengraph_image', 'eden_engine_filter_journal_index_social_image', 35 );
add_filter( 'wpseo_twitter_image', 'eden_engine_filter_journal_index_social_image', 35 );
add_filter( 'rank_math/opengraph/facebook/og_image', 'eden_engine_filter_journal_index_social_image', 35 );
add_filter( 'rank_math/opengraph/twitter/twitter_image', 'eden_engine_filter_journal_index_social_image', 35 );

if ( ! function_exists( 'eden_engine_journal_index_head_metadata' ) ) {
    function eden_engine_journal_index_head_metadata(): void {
        if ( ! is_home() || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }

        $metadata = eden_engine_journal_index_metadata();
        $title    = (string) $metadata['title'] . ' | ' . get_bloginfo( 'name' );
        $schema   = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            'name'        => $title,
            'description' => (string) $metadata['description'],
            'url'         => (string) $metadata['url'],
            'image'       => (string) $metadata['image'],
            'isPartOf'    => array(
                '@type' => 'WebSite',
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url( '/' ),
            ),
        );

        echo '<link rel="canonical" href="' . esc_url( (string) $metadata['url'] ) . '" />' . "\n";
        echo '<meta name="description" content="' . esc_attr( (string) $metadata['description'] ) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( (string) $metadata['description'] ) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( (string) $metadata['url'] ) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url( (string) $metadata['image'] ) . '" />' . "\n";
        echo '<meta property="og:image:alt" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( (string) $metadata['description'] ) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url( (string) $metadata['image'] ) . '" />' . "\n";
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}

add_action( 'wp_head', 'eden_engine_journal_index_head_metadata', 5 );

if ( ! function_exists( 'eden_engine_is_historical_journal_post' ) ) {
    function eden_engine_is_historical_journal_post( int $post_id ): bool {
        if ( 'post' !== get_post_type( $post_id ) ) {
            return false;
        }

        $published_date    = (string) get_post_field( 'post_date', $post_id );
        $post_slug         = (string) get_post_field( 'post_name', $post_id );
        $historical_slugs  = array(
            'what-carbon-negative-food-actually-means',
            'why-co2-to-sugar-is-the-first-step-toward-post-agricultural-humanity',
            'the-eden-engine-phase-1-roadmap-from-co2-to-sugar',
        );
        $is_historical     = '' !== $published_date && $published_date < '2026-05-01 00:00:00';
        $is_historical     = $is_historical || in_array( $post_slug, $historical_slugs, true );

        return (bool) apply_filters( 'eden_engine_is_historical_journal_post', $is_historical, $post_id );
    }
}

if ( ! function_exists( 'eden_engine_historical_journal_notice' ) ) {
    function eden_engine_historical_journal_notice(): string {
        return '<aside class="eden-journal-review-note" aria-labelledby="eden-journal-review-note-title">'
            . '<p class="eden-journal-eyebrow">Historical vision note</p>'
            . '<h2 id="eden-journal-review-note-title">Earlier concept / not current capability</h2>'
            . '<p><strong>Claim class:</strong> historical vision / future research target.</p>'
            . '<p>This article reflects an earlier Eden Engine concept. The current program separates Phase 1A microbial-biomass validation from a parallel, higher-risk Phase 1B carbon-to-carbohydrate program. No production, safety, environmental-impact, or scale claim should be inferred from this article.</p>'
            . '<p><strong>Last technically reviewed:</strong> <time datetime="2026-08-29">August 29, 2026</time></p>'
            . '<a href="' . esc_url( home_url( '/evidence/' ) ) . '">View the current evidence program</a>'
            . '</aside>';
    }
}

if ( ! function_exists( 'eden_engine_sugar_safety_review_content' ) ) {
    function eden_engine_sugar_safety_review_content(): string {
        $proof_items = array(
            'Molecular identity of the intended output',
            'Source-gas origin, contaminants, and conditioning',
            'Process-derived impurities and removal steps',
            'Downstream purification and food-grade specifications',
            'Batch consistency and analytical method qualification',
            'Hazard analysis, toxicology, and exposure where applicable',
            'Intended use and expected consumption',
            'Shelf-life and stability logic',
            'Applicable regulatory pathway',
            'Eden-specific measured evidence and independent review',
        );
        $list_html = '';

        foreach ( $proof_items as $proof_item ) {
            $list_html .= '<li>' . esc_html( $proof_item ) . '</li>';
        }

        return '<section class="eden-journal-correction" aria-labelledby="eden-safety-review-title">'
            . '<p class="eden-journal-eyebrow">Current technical answer</p>'
            . '<h2 id="eden-safety-review-title">Safety cannot be answered categorically before the product and process are defined.</h2>'
            . '<p>Chemical identity matters, but it is not the whole safety or regulatory question. A specific output, manufacturing route, impurity profile, intended use, specification, and evidence package must be evaluated together.</p>'
            . '<h3>What would need to be demonstrated</h3>'
            . '<ol>' . $list_html . '</ol>'
            . '<p><strong>Current Eden status:</strong> no Eden output is publicly claimed to be food-grade, safe for consumption, or regulatorily cleared. The smallest useful next step remains a bounded, instrumented carbon-to-ingredient experiment.</p>'
            . '<a class="eden-journal-button" href="' . esc_url( home_url( '/evidence/' ) ) . '">View the evidence program</a>'
            . '</section>';
    }
}

if ( ! function_exists( 'eden_engine_apply_journal_review_policy' ) ) {
    function eden_engine_apply_journal_review_policy( string $content ): string {
        if ( is_admin() || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
            return $content;
        }

        $post_id   = get_the_ID();
        $post_slug = (string) get_post_field( 'post_name', $post_id );

        if ( 'is-co2-derived-sugar-safe-to-eat' === $post_slug ) {
            return eden_engine_historical_journal_notice() . eden_engine_sugar_safety_review_content();
        }

        if ( eden_engine_is_historical_journal_post( $post_id ) ) {
            return eden_engine_historical_journal_notice() . $content;
        }

        return $content;
    }
}

add_filter( 'the_content', 'eden_engine_apply_journal_review_policy', 30 );

if ( ! function_exists( 'eden_engine_reviewed_journal_excerpt' ) ) {
    function eden_engine_reviewed_journal_excerpt( string $excerpt, $post ): string {
        if ( ! $post instanceof WP_Post ) {
            return $excerpt;
        }

        $policy = eden_engine_reviewed_journal_policy_for_post( (int) $post->ID );

        return ! empty( $policy['description'] ) ? (string) $policy['description'] : $excerpt;
    }
}

add_filter( 'get_the_excerpt', 'eden_engine_reviewed_journal_excerpt', 20, 2 );
