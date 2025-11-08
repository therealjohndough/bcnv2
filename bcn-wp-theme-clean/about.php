<?php
/**
 * About Page Template
 * 
 * Comprehensive About page for Buffalo Cannabis Network
 * Uses the provided content structure with internal linking
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main bcn-about-page">
    
    <!-- Breadcrumbs -->
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        <?php echo bcn_render_breadcrumbs('about'); ?>
    </div>
    
    <!-- Page Header -->
    <div class="page-header" style="
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 4rem 0;
        text-align: center;
    ">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <h1 class="page-title" style="
                font-size: 3.5rem;
                margin-bottom: 1rem;
                font-weight: 700;
                line-height: 1.2;
            ">About the Buffalo Cannabis Network (BCN)</h1>
        </div>
    </div>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Section 1: Introduction -->
        <section class="about-intro" style="padding: 4rem 0;">
            <div class="intro-content" style="
                background: white;
                padding: 3rem;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.08);
                text-align: center;
            ">
                <h2 style="
                    font-size: 2.5rem;
                    margin-bottom: 2rem;
                    color: var(--primary-color);
                    font-weight: 600;
                ">Building Buffalo's Cannabis Future, Together.</h2>
                
                <p style="
                    font-size: 1.2rem;
                    line-height: 1.8;
                    color: var(--text-color, #666);
                    max-width: 900px;
                    margin: 0 auto;
                ">The Buffalo Cannabis Network (BCN) is the dedicated hub for professionals shaping the future of cannabis in Western New York. We are more than just an association; we are a dynamic community founded on the principles of connection, mutual support, and collective elevation. In a rapidly evolving industry, BCN provides the essential platform for collaboration, knowledge-sharing, and unified growth for businesses and individuals across the entire cannabis ecosystem.</p>
            </div>
        </section>

        <!-- Section 2: Our Story -->
        <section class="about-story" style="padding: 4rem 0; background: var(--light-bg, #f8f9fa);">
            <div class="story-content" style="
                background: white;
                padding: 3rem;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            ">
                <h2 style="
                    font-size: 2.25rem;
                    margin-bottom: 2rem;
                    color: var(--primary-color);
                    font-weight: 600;
                    text-align: center;
                ">From Seedling Idea to Thriving Network</h2>
                
                <p style="
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: var(--text-color, #666);
                ">BCN was born from a recognized need within Buffalo's burgeoning cannabis landscape. As the industry took root, pioneers, entrepreneurs, and ancillary businesses faced unique challenges – navigating complex regulations, establishing best practices, and building vital connections in isolation. We saw an incredible opportunity: to create a central organization where these passionate individuals could come together, share their expertise, overcome obstacles collaboratively, and build a stronger, more respected industry for the entire region. Founded by a group of dedicated local industry professionals, BCN is committed to fostering an environment where every stakeholder has the opportunity to flourish.</p>
            </div>
        </section>

        <!-- Section 3: Mission & Vision -->
        <section class="about-mission" style="padding: 4rem 0;">
            <div class="mission-content" style="
                background: white;
                padding: 3rem;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            ">
                <h2 style="
                    font-size: 2.25rem;
                    margin-bottom: 2rem;
                    color: var(--primary-color);
                    font-weight: 600;
                    text-align: center;
                ">Our Guiding Purpose</h2>
                
                <div class="mission-statement" style="margin-bottom: 3rem;">
                    <h3 style="
                        font-size: 1.5rem;
                        margin-bottom: 1rem;
                        color: var(--secondary-color);
                        font-weight: 600;
                    ">Mission</h3>
                    <p style="
                        font-size: 1.1rem;
                        line-height: 1.8;
                        color: var(--text-color, #666);
                        margin-bottom: 1.5rem;
                    ">Our mission is clear: Connect, Support, and Elevate the Buffalo cannabis industry. We achieve this by:</p>
                    
                    <ul style="
                        list-style: none;
                        padding: 0;
                        margin: 0;
                    ">
                        <li style="
                            display: flex;
                            align-items: flex-start;
                            margin-bottom: 1rem;
                            padding-left: 2rem;
                            position: relative;
                        ">
                            <span style="
                                position: absolute;
                                left: 0;
                                top: 0.5rem;
                                width: 8px;
                                height: 8px;
                                background: var(--accent-color);
                                border-radius: 50%;
                            "></span>
                            <span style="
                                font-size: 1.1rem;
                                line-height: 1.8;
                                color: var(--text-color, #666);
                            ">Connecting professionals through meaningful networking and collaborative opportunities.</span>
                        </li>
                        <li style="
                            display: flex;
                            align-items: flex-start;
                            margin-bottom: 1rem;
                            padding-left: 2rem;
                            position: relative;
                        ">
                            <span style="
                                position: absolute;
                                left: 0;
                                top: 0.5rem;
                                width: 8px;
                                height: 8px;
                                background: var(--accent-color);
                                border-radius: 50%;
                            "></span>
                            <span style="
                                font-size: 1.1rem;
                                line-height: 1.8;
                                color: var(--text-color, #666);
                            ">Supporting members with essential resources, timely information, and valuable education.</span>
                        </li>
                        <li style="
                            display: flex;
                            align-items: flex-start;
                            margin-bottom: 1rem;
                            padding-left: 2rem;
                            position: relative;
                        ">
                            <span style="
                                position: absolute;
                                left: 0;
                                top: 0.5rem;
                                width: 8px;
                                height: 8px;
                                background: var(--accent-color);
                                border-radius: 50%;
                            "></span>
                            <span style="
                                font-size: 1.1rem;
                                line-height: 1.8;
                                color: var(--text-color, #666);
                            ">Elevating industry standards, advocating for responsible policy, and promoting the collective success of our members and the community.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="vision-statement">
                    <h3 style="
                        font-size: 1.5rem;
                        margin-bottom: 1rem;
                        color: var(--secondary-color);
                        font-weight: 600;
                    ">Vision</h3>
                    <p style="
                        font-size: 1.1rem;
                        line-height: 1.8;
                        color: var(--text-color, #666);
                    ">We envision a Western New York cannabis industry recognized for its professionalism, innovation, collaboration, and positive community impact. BCN strives to be the leading voice and indispensable resource driving this vision forward, ensuring a sustainable and thriving future for cannabis in Buffalo.</p>
                </div>
            </div>
        </section>

        <!-- Section 4: Core Values -->
        <section class="about-values" style="padding: 4rem 0; background: var(--light-bg, #f8f9fa);">
            <div class="values-content" style="
                background: white;
                padding: 3rem;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            ">
                <h2 style="
                    font-size: 2.25rem;
                    margin-bottom: 2rem;
                    color: var(--primary-color);
                    font-weight: 600;
                    text-align: center;
                ">The Principles We Cultivate</h2>
                
                <p style="
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: var(--text-color, #666);
                    text-align: center;
                    margin-bottom: 3rem;
                ">Everything we do at BCN is guided by these core values:</p>
                
                <div class="values-grid" style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    gap: 2rem;
                ">
                    <div class="value-card" style="
                        background: var(--light-bg, #f8f9fa);
                        padding: 2rem;
                        border-radius: 12px;
                        border-left: 4px solid var(--accent-color);
                    ">
                        <h3 style="
                            font-size: 1.25rem;
                            margin-bottom: 1rem;
                            color: var(--primary-color);
                            font-weight: 600;
                        ">Community</h3>
                        <p style="
                            font-size: 1rem;
                            line-height: 1.7;
                            color: var(--text-color, #666);
                        ">We believe in the power of connection. We foster an inclusive, welcoming environment where members build authentic relationships, share experiences, and support one another's success. We are stronger together.</p>
                    </div>
                    
                    <div class="value-card" style="
                        background: var(--light-bg, #f8f9fa);
                        padding: 2rem;
                        border-radius: 12px;
                        border-left: 4px solid var(--accent-color);
                    ">
                        <h3 style="
                            font-size: 1.25rem;
                            margin-bottom: 1rem;
                            color: var(--primary-color);
                            font-weight: 600;
                        ">Collaboration</h3>
                        <p style="
                            font-size: 1rem;
                            line-height: 1.7;
                            color: var(--text-color, #666);
                        ">Progress thrives on partnership. We champion collaboration not just among members, but with regulators, community leaders, and other stakeholders to achieve shared goals and overcome common challenges.</p>
                    </div>
                    
                    <div class="value-card" style="
                        background: var(--light-bg, #f8f9fa);
                        padding: 2rem;
                        border-radius: 12px;
                        border-left: 4px solid var(--accent-color);
                    ">
                        <h3 style="
                            font-size: 1.25rem;
                            margin-bottom: 1rem;
                            color: var(--primary-color);
                            font-weight: 600;
                        ">Shared Growth</h3>
                        <p style="
                            font-size: 1rem;
                            line-height: 1.7;
                            color: var(--text-color, #666);
                        ">We are committed to the collective advancement of the entire Buffalo cannabis sector. By sharing knowledge, promoting best practices, and advocating effectively, we aim to elevate all members and ensure the long-term health and reputation of the local industry.</p>
                    </div>
                    
                    <div class="value-card" style="
                        background: var(--light-bg, #f8f9fa);
                        padding: 2rem;
                        border-radius: 12px;
                        border-left: 4px solid var(--accent-color);
                    ">
                        <h3 style="
                            font-size: 1.25rem;
                            margin-bottom: 1rem;
                            color: var(--primary-color);
                            font-weight: 600;
                        ">Integrity & Responsibility</h3>
                        <p style="
                            font-size: 1rem;
                            line-height: 1.7;
                            color: var(--text-color, #666);
                        ">We uphold the highest standards of professionalism and ethical conduct. We advocate for responsible business practices and work to ensure the Buffalo cannabis industry operates with transparency and accountability.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5: Leadership (Optional) -->
        <section class="about-leadership" style="padding: 4rem 0;">
            <div class="leadership-content" style="
                background: white;
                padding: 3rem;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.08);
                text-align: center;
            ">
                <h2 style="
                    font-size: 2.25rem;
                    margin-bottom: 2rem;
                    color: var(--primary-color);
                    font-weight: 600;
                ">Dedicated Leadership</h2>
                
                <p style="
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: var(--text-color, #666);
                    max-width: 800px;
                    margin: 0 auto;
                ">BCN is guided by a dedicated team of passionate industry professionals committed to serving our members and advancing our mission. Our leadership brings together diverse expertise and shared vision to drive the organization forward.</p>
            </div>
        </section>

        <!-- Section 6: Call to Action -->
        <section class="about-cta" style="
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 4rem 3rem;
            border-radius: 16px;
            text-align: center;
            color: white;
            margin: 4rem 0;
        ">
            <h2 style="
                font-size: 2.5rem;
                margin-bottom: 2rem;
                font-weight: 700;
            ">Join Us in Shaping the Future</h2>
            
            <p style="
                font-size: 1.2rem;
                line-height: 1.8;
                margin-bottom: 3rem;
                opacity: 0.9;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
            ">The Buffalo cannabis industry holds immense potential. BCN is positioned at the forefront, ready to help navigate the path ahead. We invite you to become part of this vital network. Whether you're a cultivator, processor, retailer, ancillary service provider, or aspiring entrepreneur, your voice and participation are crucial. Together, we can continue to build a prosperous, professional, and respected cannabis community in Western New York.</p>
            
            <div class="cta-buttons" style="
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
            ">
                <a href="/membership/" 
                   class="bcn-button bcn-button-primary" 
                   style="
                       background: var(--accent-color);
                       color: white;
                       padding: 1rem 2rem;
                       border-radius: 8px;
                       text-decoration: none;
                       font-weight: 600;
                       display: inline-block;
                       transition: all 0.3s ease;
                   ">Explore Membership Benefits</a>
                
                <a href="/contact/" 
                   class="bcn-button bcn-button-secondary" 
                   style="
                       background: transparent;
                       color: white;
                       padding: 1rem 2rem;
                       border: 2px solid white;
                       border-radius: 8px;
                       text-decoration: none;
                       font-weight: 600;
                       display: inline-block;
                       transition: all 0.3s ease;
                   ">Contact Us with Questions</a>
            </div>
        </section>
        
        <!-- Related Content Section -->
        <?php echo bcn_render_related_content('about'); ?>
        
    </div>
</main>

<style>
.bcn-about-page .bcn-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.bcn-about-page .bcn-button-secondary:hover {
    background: white;
    color: var(--primary-color);
}

@media (max-width: 768px) {
    .bcn-about-page .page-title {
        font-size: 2.5rem !important;
    }
    
    .bcn-about-page .intro-content,
    .bcn-about-page .story-content,
    .bcn-about-page .mission-content,
    .bcn-about-page .values-content,
    .bcn-about-page .leadership-content {
        padding: 2rem !important;
    }
    
    .bcn-about-page .values-grid {
        grid-template-columns: 1fr !important;
    }
    
    .bcn-about-page .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .bcn-about-page .bcn-button {
        width: 100%;
        max-width: 300px;
        text-align: center;
    }
}
</style>

<?php
get_footer();
