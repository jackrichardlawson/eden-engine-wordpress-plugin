<?php
/**
 * Reviewed, artifact-backed public Journal publications.
 *
 * These entries publish constrained summaries and checksum receipts. They do
 * not expose the protected source repository or promote planning/synthetic
 * records into physical Eden evidence.
 *
 * @package EdenEngine
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'eden_engine_public_artifact_manifest_url' ) ) {
    function eden_engine_public_artifact_manifest_url(): string {
        return EDEN_ENGINE_PLUGIN_URL . 'public-artifacts/2026-08-29-journal-proof-manifest.json';
    }
}

if ( ! function_exists( 'eden_engine_seeded_artifact_publications' ) ) {
    function eden_engine_seeded_artifact_publications(): array {
        $manifest_url = eden_engine_public_artifact_manifest_url();

        return array(
            'what-exp-bal-gas-must-measure-before-it-counts-as-eden-evidence' => array(
                'title'       => 'What EXP-BAL-GAS Must Measure Before It Counts as Eden Evidence',
                'excerpt'     => 'The reviewed EXP-BAL-GAS readiness package defines the co-measured gas, biomass, composition, control, uncertainty, and HOLD conditions required before a run could update an Eden parameter.',
                'boundary'    => 'Completed planning and data-governance work. Physical execution remains WAITING_HUMAN; no result, yield, safety, or food claim is reported.',
                'sections'    => array(
                    array(
                        'title' => 'The experiment boundary',
                        'copy'  => array(
                            'EXP-BAL-GAS is one bounded, instrumented H₂/O₂/CO₂ balance run designed to maximize decision-relevant information rather than produce a success-looking growth curve.',
                            'Gas supply and off-gas must be measured in the same window as cell dry weight. End-of-run composition adds protein, PHB, moisture, and ash so apparent biomass cannot silently substitute for the intended ingredient evidence.',
                        ),
                        'items' => array(
                            'H₂, O₂, and CO₂ inlet flows, outlet flows, composition, and integrated totals',
                            'Cell dry weight and OD over time, with working volume and dry-weight basis preserved',
                            'Protein and PHB fractions with method, calibration, blanks, and recovery checks',
                            'Temperature, pH, dissolved oxygen, pressure, media charges, and contamination indicators',
                            'Raw provenance, calibration references, transform logs, quality flags, and uncertainty',
                        ),
                    ),
                    array(
                        'title' => 'The pre-written HOLD rule',
                        'copy'  => array(
                            'A candidate run cannot become MEASURED_EDEN merely because biomass appears. Calibration, contamination disposition, impossible-data checks, and gas/mass closure within propagated uncertainty must all pass.',
                            'If gas uptake and cell dry weight are not co-measured, Eden does not publish an H₂ yield as measured. If the balance does not close, the program reconciles instrumentation, narrows the boundary, repeats, reroutes, or remains on HOLD.',
                        ),
                    ),
                    array(
                        'title' => 'Current status and next gate',
                        'copy'  => array(
                            'The production run template is intentionally empty: every observation value is null and measured_eden_parameters is empty. Numerical integrity remains PARTIAL.',
                            'The smallest useful next test starts only after HDQ-001 identity-qualified strain accession and HDQ-004/D05 H₂/O₂ hazard approval. After the first accepted run, at least one biological replicate is intended before any design target is frozen; the exact replicate count remains unassigned pending qualified review.',
                        ),
                    ),
                ),
                'tags'         => array( 'Build Log', 'EXP-BAL-GAS', 'Experiment Readiness', 'Evidence Gates' ),
                'contract'     => array(
                    '_eden_journal_artifact_identifier'  => 'eden-publication-receipt-exp-bal-gas-measurements-v1',
                    '_eden_journal_artifact_url'         => $manifest_url,
                    '_eden_journal_artifact_type'        => 'protocol-or-method',
                    '_eden_journal_artifact_status'      => 'published',
                    '_eden_journal_claim_state'          => 'planned-validation',
                    '_eden_journal_what_changed'         => 'The first experiment was narrowed to co-measured gas totals, CDW, protein, PHB, provenance, uncertainty, controls, and a pre-written HOLD rule.',
                    '_eden_journal_narrow_support'       => 'Eden has a bounded experiment and evidence contract that specifies when a candidate record may update an Eden parameter.',
                    '_eden_journal_excluded_inferences'  => array(
                        'No physical run or live H₂/O₂ gas operation occurred.',
                        'No yield, protein fraction, carbon efficiency, or repeatability was measured.',
                        'No safety, food-grade output, production performance, or Eden-wide numerical closure is claimed.',
                    ),
                    '_eden_journal_evidence_references'  => array(
                        array(
                            'label' => 'EXP-BAL-GAS reviewed artifact and checksum manifest',
                            'url'   => $manifest_url,
                            'type'  => 'Reviewed Eden artifact receipt',
                        ),
                        array(
                            'label' => 'Current Eden evidence program',
                            'url'   => home_url( '/evidence/' ),
                            'type'  => 'Public claim-state boundary',
                        ),
                    ),
                    '_eden_journal_next_gate'            => 'Authorize HDQ-001 strain accession and HDQ-004/D05 gas safety, then execute one calibrated run and qualify its carbon/gas balance before any parameter promotion.',
                    '_eden_journal_review_date'          => '2026-08-29',
                    '_eden_journal_eden_interpretation'  => 'The highest-value first run is an information experiment, not biomass-success theater.',
                    '_eden_journal_unknowns'             => array(
                        'Operating setpoints and qualified instrument tolerances remain unassigned.',
                        'No first accepted physical result exists.',
                        'The exact biological replicate count and Eden-wide numerical closure remain unresolved.',
                    ),
                ),
            ),
            'why-synthetic-data-can-test-edens-pipeline-but-cannot-become-measured-eden-evidence' => array(
                'title'       => 'Why Synthetic Data Can Test Eden’s Pipeline but Cannot Become Measured Eden Evidence',
                'excerpt'     => 'Synthetic fixtures can test ingestion, conservation, provenance, replay, model wiring, and failure behavior. Eden’s promotion gate keeps every synthetic value out of MEASURED_EDEN.',
                'boundary'    => 'Implemented and regression-tested software behavior using SYNTHETIC_TEST_DATA. No biology, reactor, analytical method, yield, economics, or safety result is reported.',
                'sections'    => array(
                    array(
                        'title' => 'What synthetic data is allowed to prove',
                        'copy'  => array(
                            'A synthetic fixture is useful when the question is whether software accepts a schema, preserves units, seals provenance, detects impossible values, replays deterministically, or fails safely.',
                            'Eden runs synthetic observations through the same qualification and model-consumer plumbing used by a future instrument record. That exercises interfaces and conservation logic without changing the source’s evidence class.',
                        ),
                        'items' => array(
                            'Ingestion and schema validation work end to end',
                            'Unit and conservation checks run against expected boundaries',
                            'Provenance hashes, replay, rollback, and failure states remain traceable',
                            'Real model functions can be exercised in SHADOW mode',
                            'Known missing consumers are recorded as DOWNSTREAM_MODEL_NOT_IMPLEMENTED rather than fabricated',
                        ),
                    ),
                    array(
                        'title' => 'The line synthetic data cannot cross',
                        'copy'  => array(
                            'Synthetic observations may create qualified shadow events and may move a sensitivity score, but measured_eden_parameters remains empty. A shadow score change cannot reopen a campaign.',
                            'Pipeline test doubles require the exact non-production gate EDEN_ALLOW_PIPELINE_TEST_DOUBLES=1. Production refuses them, and any gated test-double record remains tagged PIPELINE_TEST_DOUBLE_NOT_REAL_EDEN.',
                        ),
                    ),
                    array(
                        'title' => 'The next observation that would change the claim',
                        'copy'  => array(
                            'Only qualified real-instrument observations from an authorized physical run can approach MEASURED_EDEN. They must pass the same calibration, contamination, closure, provenance, and promotion gates exercised by the synthetic path.',
                            'Until that happens, the defensible public claim is narrow: Eden has tested evidence plumbing and a fail-closed promotion boundary. It has not produced physical process evidence.',
                        ),
                    ),
                ),
                'tags'         => array( 'Build Log', 'Synthetic Data', 'Data Provenance', 'Evidence Gates' ),
                'contract'     => array(
                    '_eden_journal_artifact_identifier'  => 'eden-publication-receipt-synthetic-promotion-boundary-v1',
                    '_eden_journal_artifact_url'         => $manifest_url,
                    '_eden_journal_artifact_type'        => 'software-release',
                    '_eden_journal_artifact_status'      => 'published',
                    '_eden_journal_claim_state'          => 'synthetic',
                    '_eden_journal_what_changed'         => 'The experiment pipeline now qualifies, seals, replays, recalculates, and shadow-rescores synthetic records while hard-refusing their promotion into measured Eden evidence.',
                    '_eden_journal_narrow_support'       => 'Synthetic data validates software plumbing, conservation checks, provenance, replay, model wiring, and fail-closed behavior.',
                    '_eden_journal_excluded_inferences'  => array(
                        'Pipeline success does not validate the biology, reactor, or analytical method.',
                        'A model-output change under synthetic inputs does not validate model accuracy, yield, or economics.',
                        'No safety, food-suitability, or physical Eden result is claimed.',
                    ),
                    '_eden_journal_evidence_references'  => array(
                        array(
                            'label' => 'Synthetic promotion-boundary artifact and checksum manifest',
                            'url'   => $manifest_url,
                            'type'  => 'Reviewed Eden artifact receipt',
                        ),
                        array(
                            'label' => 'Current Eden evidence program',
                            'url'   => home_url( '/evidence/' ),
                            'type'  => 'Public evidence taxonomy',
                        ),
                    ),
                    '_eden_journal_next_gate'            => 'Land qualified observations from an authorized real-instrument run and pass QC, contamination, balance-closure, provenance, and promotion gates.',
                    '_eden_journal_review_date'          => '2026-08-29',
                    '_eden_journal_eden_interpretation'  => 'Synthetic evidence can reduce software uncertainty while remaining categorically separate from physical process evidence.',
                    '_eden_journal_unknowns'             => array(
                        'Real observation quality and repeatability remain unknown.',
                        'Model accuracy against physical data and process performance remain unknown.',
                        'Named downstream model gaps remain unresolved.',
                    ),
                ),
            ),
            'how-edens-current-evidence-gated-decision-protocol-works' => array(
                'title'       => 'How Eden’s Current Evidence-Gated Decision Protocol Works',
                'excerpt'     => 'Eden separates observation qualification, measured-parameter promotion, model and uncertainty updates, and campaign reprioritization. Missing evidence or failed gates lead to repeat, reroute, or HOLD.',
                'boundary'    => 'Implemented internal governance and decision-support logic. The protocol and scoring rules are planning controls, not independently validated scientific truth or proof that a pathway works.',
                'sections'    => array(
                    array(
                        'title' => 'Four different decisions stay separate',
                        'copy'  => array(
                            'Eden does not let one optimistic readiness label carry an observation from source to strategy. Each transition has its own authority and failure rule.',
                        ),
                        'items' => array(
                            'Qualify the observation: schema, units, calibration, contamination, provenance, and impossible-data checks',
                            'Promote an Eden parameter only when the evidence class and acceptance gates permit it',
                            'Recalculate named model consumers and uncertainty while recording missing consumers explicitly',
                            'Reprioritize a campaign only after an eligible change, with human authority preserved for consequential actions',
                        ),
                    ),
                    array(
                        'title' => 'What stops advancement',
                        'copy'  => array(
                            'Synthetic data can shadow-rescore priorities but cannot reopen a campaign. Impossible values cannot control architecture, economics, or public claims. Missing consumer models remain gaps rather than receiving invented updates.',
                            'A higher internal score is not pathway proof. HOLD status, contamination, failed closure, missing models, safety gates, and absent human authorization all stop advancement even when a candidate looks attractive.',
                        ),
                    ),
                    array(
                        'title' => 'The next gate that matters',
                        'copy'  => array(
                            'The current protocol points back to the same smallest useful step: land one qualified physical record, review the uncertainty update and scoring assumptions, and require human review before any campaign reopen, organism selection, live gas operation, purchase, or public performance claim.',
                            'The numerical-integrity certificate stays PARTIAL until its named open subsystems close. Evidence gates determine what advances; the calendar does not.',
                        ),
                    ),
                ),
                'tags'         => array( 'Build Log', 'Decision Protocol', 'Evidence Gates', 'Research Governance' ),
                'contract'     => array(
                    '_eden_journal_artifact_identifier'  => 'eden-publication-receipt-evidence-gated-decision-protocol-v1',
                    '_eden_journal_artifact_url'         => $manifest_url,
                    '_eden_journal_artifact_type'        => 'protocol-or-method',
                    '_eden_journal_artifact_status'      => 'published',
                    '_eden_journal_claim_state'          => 'implemented-governance',
                    '_eden_journal_what_changed'         => 'Observation qualification, parameter promotion, model/uncertainty updates, and campaign reprioritization now have distinct authorities, stop conditions, and traceable fallbacks.',
                    '_eden_journal_narrow_support'       => 'Eden has an explicit, traceable internal protocol for continue, repeat, reroute, or HOLD decisions.',
                    '_eden_journal_excluded_inferences'  => array(
                        'The scoring rule is not independently validated as the optimal scientific-ranking method.',
                        'A higher internal score does not prove feasibility or pathway superiority.',
                        'No safety, economics, regulatory acceptance, or experimental advancement is implied.',
                    ),
                    '_eden_journal_evidence_references'  => array(
                        array(
                            'label' => 'Evidence-gated decision-protocol artifact and checksum manifest',
                            'url'   => $manifest_url,
                            'type'  => 'Reviewed Eden artifact receipt',
                        ),
                        array(
                            'label' => 'Public evidence-gated roadmap',
                            'url'   => home_url( '/roadmap/' ),
                            'type'  => 'Public decision-gate summary',
                        ),
                    ),
                    '_eden_journal_next_gate'            => 'Land a qualified physical record, review scoring and uncertainty assumptions, then require human approval for any campaign reopen or public performance claim.',
                    '_eden_journal_review_date'          => '2026-08-29',
                    '_eden_journal_eden_interpretation'  => 'Decision quality improves when evidence promotion, model change, prioritization, and authorization remain separate.',
                    '_eden_journal_unknowns'             => array(
                        'Physical observations and external validation of the ranking method remain absent.',
                        'Named downstream consumers and final organism selection remain open.',
                        'The numerical-integrity certificate remains PARTIAL.',
                    ),
                ),
            ),
        );
    }
}

if ( ! function_exists( 'eden_engine_seeded_artifact_publication_content' ) ) {
    function eden_engine_seeded_artifact_publication_content( array $publication ): string {
        $manifest_url = eden_engine_public_artifact_manifest_url();
        $html         = '<p><strong>Publication boundary:</strong> ' . esc_html( (string) $publication['boundary'] ) . '</p>';

        foreach ( (array) $publication['sections'] as $section ) {
            $html .= '<h2>' . esc_html( (string) $section['title'] ) . '</h2>';

            foreach ( (array) ( $section['copy'] ?? array() ) as $paragraph ) {
                $html .= '<p>' . esc_html( (string) $paragraph ) . '</p>';
            }

            if ( ! empty( $section['items'] ) ) {
                $html .= '<ul>';
                foreach ( (array) $section['items'] as $item ) {
                    $html .= '<li>' . esc_html( (string) $item ) . '</li>';
                }
                $html .= '</ul>';
            }
        }

        $html .= '<h2>Artifact receipt</h2>';
        $html .= '<p>The supporting repository is protected. Eden publishes the reviewed artifact identifiers, dates, source locators, and SHA-256 receipts without presenting the underlying planning files as public measurements.</p>';
        $html .= '<p><a href="' . esc_url( $manifest_url ) . '">Open the machine-readable artifact and checksum manifest</a></p>';
        $html .= '<p><strong>Technical review date:</strong> <time datetime="2026-08-29">August 29, 2026</time>.</p>';

        return $html;
    }
}

if ( ! function_exists( 'eden_engine_seeded_publication_author_id' ) ) {
    function eden_engine_seeded_publication_author_id(): int {
        $configured = (int) get_option( 'eden_engine_publication_author_id', 0 );

        if ( $configured > 0 && get_user_by( 'id', $configured ) ) {
            return $configured;
        }

        $users = get_users(
            array(
                'role__in' => array( 'administrator', 'editor' ),
                'number'   => 1,
                'fields'   => 'ID',
                'orderby'  => 'ID',
                'order'    => 'ASC',
            )
        );

        return empty( $users ) ? 0 : (int) $users[0];
    }
}

if ( ! function_exists( 'eden_engine_seed_artifact_publications' ) ) {
    function eden_engine_seed_artifact_publications(): void {
        $seed_version = '2026-08-29-v1';
        $option_name  = 'eden_engine_artifact_publications_seed_version';
        $lock_name    = 'eden_engine_artifact_publications_seed_lock';

        if ( get_option( $option_name ) === $seed_version ) {
            return;
        }

        $lock_timestamp = (int) get_option( $lock_name, 0 );
        if ( $lock_timestamp > 0 && ( time() - $lock_timestamp ) > 300 ) {
            delete_option( $lock_name );
        }

        if ( ! add_option( $lock_name, time(), '', 'no' ) ) {
            return;
        }

        $all_complete = true;
        $category = term_exists( 'Build Log', 'category' );
        if ( ! $category ) {
            $category = wp_insert_term( 'Build Log', 'category', array( 'slug' => 'build-log' ) );
        }

        $category_id = 0;
        if ( is_wp_error( $category ) ) {
            $all_complete = false;
        } elseif ( is_array( $category ) && ! empty( $category['term_id'] ) ) {
            $category_id = (int) $category['term_id'];
        } elseif ( is_int( $category ) ) {
            $category_id = $category;
        }

        if ( $category_id <= 0 ) {
            delete_option( $lock_name );
            return;
        }

        $author_id = eden_engine_seeded_publication_author_id();

        foreach ( eden_engine_seeded_artifact_publications() as $slug => $publication ) {
            $existing = get_page_by_path( $slug, OBJECT, 'post' );
            $post_id  = $existing instanceof WP_Post ? (int) $existing->ID : 0;

            if ( $post_id && '1' !== (string) get_post_meta( $post_id, '_eden_engine_seeded_artifact_publication', true ) ) {
                $all_complete = false;
                continue;
            }

            if (
                $post_id &&
                $seed_version === (string) get_post_meta( $post_id, '_eden_engine_seeded_artifact_publication_version', true ) &&
                'publish' === get_post_status( $post_id ) &&
                eden_engine_is_artifact_backed_post( $post_id )
            ) {
                continue;
            }

            $post_data = array(
                'post_title'   => (string) $publication['title'],
                'post_name'    => $slug,
                'post_excerpt' => (string) $publication['excerpt'],
                'post_content' => eden_engine_seeded_artifact_publication_content( $publication ),
                'post_status'  => 'draft',
                'post_type'    => 'post',
            );

            if ( $category_id > 0 ) {
                $post_data['post_category'] = array( $category_id );
            }

            if ( $author_id > 0 ) {
                $post_data['post_author'] = $author_id;
            }

            if ( $post_id ) {
                $post_data['ID'] = $post_id;
                $post_id         = wp_update_post( wp_slash( $post_data ), true );
            } else {
                $post_id = wp_insert_post( wp_slash( $post_data ), true );
            }

            if ( is_wp_error( $post_id ) || ! $post_id ) {
                $all_complete = false;
                continue;
            }

            update_post_meta( (int) $post_id, '_eden_engine_seeded_artifact_publication', '1' );
            foreach ( (array) $publication['contract'] as $meta_key => $meta_value ) {
                update_post_meta( (int) $post_id, (string) $meta_key, $meta_value );
            }

            $tag_result = wp_set_post_tags( (int) $post_id, (array) $publication['tags'], false );
            if ( is_wp_error( $tag_result ) ) {
                $all_complete = false;
                continue;
            }

            clean_post_cache( (int) $post_id );

            $contract = eden_engine_journal_publication_contract_for_post( (int) $post_id );
            if ( empty( $contract['artifact_backed'] ) ) {
                $all_complete = false;
                continue;
            }

            $publish_result = wp_update_post(
                array(
                    'ID'          => (int) $post_id,
                    'post_status' => 'publish',
                ),
                true
            );

            if ( is_wp_error( $publish_result ) || ! $publish_result ) {
                $all_complete = false;
                continue;
            }

            update_post_meta( (int) $post_id, '_eden_engine_seeded_artifact_publication_version', $seed_version );
            clean_post_cache( (int) $post_id );
        }

        if ( $all_complete ) {
            update_option( $option_name, $seed_version, false );
        }

        delete_option( $lock_name );
    }
}

add_action( 'init', 'eden_engine_seed_artifact_publications', 30 );
