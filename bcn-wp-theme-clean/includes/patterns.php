<?php
/**
 * BCN Block Patterns
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bcn_register_block_patterns() {

    // Category
  register_block_pattern_category(
    'bcn',
    [ 'label' => __( 'BCN', 'bcn-wp-theme' ) ]
  );

    // Contact – Get in Touch (slick glass cards)
    register_block_pattern(
        'bcn/contact-get-in-touch',
        [
      'title'       => __( 'Contact – Get in Touch (BCN)', 'bcn-wp-theme' ),
      'description' => __( 'Four-card slick contact section: inquiries, membership, events/education, mailing address.', 'bcn-wp-theme' ),
            'categories'  => [ 'bcn' ],
            'content'     => '
<!-- wp:group {"tagName":"section","className":"bcn-contact-section slick","layout":{"type":"constrained"}} -->
<section class="bcn-contact-section slick">
  <!-- wp:heading {"level":2,"className":"bcn-section-title"} --><h2 class="bcn-section-title">Get in Touch</h2><!-- /wp:heading -->
  <!-- wp:paragraph {"className":"bcn-section-sub"} --><p class="bcn-section-sub">Events, education, partnerships — we’ll point you to the right human fast.</p><!-- /wp:paragraph -->
  <!-- wp:columns {"className":"bcn-grid"} -->
  <div class="wp-block-columns bcn-grid">
    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true"></span>
        <h3 class="bcn-card-title">General Inquiries</h3>
        <p class="bcn-card-line"><a class="bcn-link" href="mailto:hello@buffalocannabisnetwork.com">hello@buffalocannabisnetwork.com</a></p>
        <p class="bcn-card-copy">For all general questions, partnerships, and press inquiries.</p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->

    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true"></span>
        <h3 class="bcn-card-title">Membership Support</h3>
        <p class="bcn-card-line"><a class="bcn-link" href="mailto:members@buffalocannabisnetwork.com">members@buffalocannabisnetwork.com</a></p>
        <p class="bcn-card-copy">Joining or renewing? We’ll guide you through the process.</p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->

    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true"></span>
        <h3 class="bcn-card-title">Event &amp; Education</h3>
        <p class="bcn-card-line"><a class="bcn-link" href="mailto:events@buffalocannabisnetwork.com">events@buffalocannabisnetwork.com</a></p>
        <p class="bcn-card-copy">Host, speak, or collaborate on educational programs.</p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->

    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true"></span>
        <h3 class="bcn-card-title">Mailing Address</h3>
        <p class="bcn-card-line">Buffalo Cannabis Network<br>Buffalo, NY</p>
        <p class="bcn-card-copy"><a class="bcn-ghost" href="/contact">Need directions? Get in touch →</a></p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->
  </div><!-- /wp:columns -->
</section>
<!-- /wp:group -->'
        ]
    );

    // Social – Stay Connected (matching style)
    register_block_pattern(
        'bcn/stay-connected',
        [
      'title'       => __( 'Stay Connected (BCN)', 'bcn-wp-theme' ),
      'description' => __( 'Slick social + newsletter block in BCN style.', 'bcn-wp-theme' ),
            'categories'  => [ 'bcn' ],
            'content'     => '
<!-- wp:group {"tagName":"section","className":"bcn-social-section slick","layout":{"type":"constrained"}} -->
<section class="bcn-social-section slick">
  <!-- wp:heading {"level":2,"className":"bcn-section-title"} --><h2 class="bcn-section-title">Stay Connected</h2><!-- /wp:heading -->
  <!-- wp:columns {"className":"bcn-grid"} -->
  <div class="wp-block-columns bcn-grid">

    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true"></span>
        <h3 class="bcn-card-title">Instagram</h3>
        <p class="bcn-card-line"><a class="bcn-link" href="https://www.instagram.com/buffalocannabisnetwork" target="_blank" rel="noopener">Follow @buffalocannabisnetwork</a></p>
        <p class="bcn-card-copy">Event photos, stories, and community updates.</p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->

    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true"></span>
        <h3 class="bcn-card-title">LinkedIn</h3>
        <p class="bcn-card-line"><a class="bcn-link" href="https://www.linkedin.com/company/buffalocannabisnetwork" target="_blank" rel="noopener">Connect on LinkedIn</a></p>
        <p class="bcn-card-copy">Professional announcements and industry news.</p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->

    <!-- wp:column --><div class="wp-block-column">
      <!-- wp:group {"className":"bcn-card glass"} --><div class="bcn-card glass">
        <span class="bcn-chip" aria-hidden="true">✉</span>
        <h3 class="bcn-card-title">Newsletter</h3>
        <p class="bcn-card-line"><a class="bcn-link" href="/newsletter">Join the mailing list</a></p>
        <p class="bcn-card-copy">Get events, education, and BCN news in your inbox.</p>
      </div><!-- /wp:group -->
    </div><!-- /wp:column -->

  </div><!-- /wp:columns -->
</section>
<!-- /wp:group -->'
        ]
    );
}
add_action( 'init', 'bcn_register_block_patterns' );
