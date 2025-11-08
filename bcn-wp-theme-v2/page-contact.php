<?php
/**
 * Contact Page Template
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

get_header(); ?>

<div class="bcn-page-wrapper">
    <!-- Page Header -->
    <section class="bcn-page-header" style="
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    ">
        <div class="bcn-page-header-overlay" style="
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
            pointer-events: none;
        "></div>
        
        <div class="container" style="
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
        ">
            <h1 class="bcn-page-title" style="
                font-size: 3rem;
                font-weight: 700;
                margin: 0 0 1rem 0;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            ">
                Contact Buffalo Cannabis Network
            </h1>
            
            <p class="bcn-page-intro" style="
                font-size: 1.25rem;
                line-height: 1.6;
                margin: 0;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
                opacity: 0.9;
            ">
                Have questions about membership, events, or educational programs? We'd love to hear from you.<br>
                Buffalo Cannabis Network (BCN) connects Western New York's cannabis professionals, brands, and advocates through networking, resources, and education. Reach out today and let's strengthen Buffalo's cannabis industry—together.
            </p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="bcn-contact-content" style="
        padding: 4rem 0;
        background: #f8f9fa;
    ">
        <div class="container" style="
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        ">
            <div class="bcn-contact-grid" style="
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4rem;
                align-items: start;
            ">
                <!-- Contact Form -->
                <div class="bcn-contact-form-wrapper" style="
                    background: white;
                    padding: 3rem;
                    border-radius: 12px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                ">
                    <h2 style="
                        font-size: 2rem;
                        font-weight: 600;
                        margin: 0 0 1.5rem 0;
                        color: #2c3e50;
                    ">Send us a Message</h2>
                    
                    <form class="bcn-contact-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" style="
                        display: grid;
                        gap: 1.5rem;
                    ">
                        <?php wp_nonce_field('bcn_contact_form', 'bcn_contact_nonce'); ?>
                        <input type="hidden" name="action" value="bcn_contact_form">
                        
                        <div class="bcn-form-row" style="
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 1rem;
                        ">
                            <div class="bcn-form-group">
                                <label for="first_name" style="
                                    display: block;
                                    font-weight: 500;
                                    margin-bottom: 0.5rem;
                                    color: #2c3e50;
                                ">First Name *</label>
                                <input type="text" id="first_name" name="first_name" required style="
                                    width: 100%;
                                    padding: 0.75rem;
                                    border: 2px solid #e9ecef;
                                    border-radius: 8px;
                                    font-size: 1rem;
                                    transition: border-color 0.3s ease;
                                " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                            </div>
                            
                            <div class="bcn-form-group">
                                <label for="last_name" style="
                                    display: block;
                                    font-weight: 500;
                                    margin-bottom: 0.5rem;
                                    color: #2c3e50;
                                ">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" required style="
                                    width: 100%;
                                    padding: 0.75rem;
                                    border: 2px solid #e9ecef;
                                    border-radius: 8px;
                                    font-size: 1rem;
                                    transition: border-color 0.3s ease;
                                " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                            </div>
                        </div>
                        
                        <div class="bcn-form-group">
                            <label for="email" style="
                                display: block;
                                font-weight: 500;
                                margin-bottom: 0.5rem;
                                color: #2c3e50;
                            ">Email Address *</label>
                            <input type="email" id="email" name="email" required style="
                                width: 100%;
                                padding: 0.75rem;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: border-color 0.3s ease;
                            " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                        </div>
                        
                        <div class="bcn-form-group">
                            <label for="phone" style="
                                display: block;
                                font-weight: 500;
                                margin-bottom: 0.5rem;
                                color: #2c3e50;
                            ">Phone Number</label>
                            <input type="tel" id="phone" name="phone" style="
                                width: 100%;
                                padding: 0.75rem;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: border-color 0.3s ease;
                            " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                        </div>
                        
                        <div class="bcn-form-group">
                            <label for="company" style="
                                display: block;
                                font-weight: 500;
                                margin-bottom: 0.5rem;
                                color: #2c3e50;
                            ">Company/Organization</label>
                            <input type="text" id="company" name="company" style="
                                width: 100%;
                                padding: 0.75rem;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: border-color 0.3s ease;
                            " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                        </div>
                        
                        <div class="bcn-form-group">
                            <label for="subject" style="
                                display: block;
                                font-weight: 500;
                                margin-bottom: 0.5rem;
                                color: #2c3e50;
                            ">Subject *</label>
                            <select id="subject" name="subject" required style="
                                width: 100%;
                                padding: 0.75rem;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                font-size: 1rem;
                                transition: border-color 0.3s ease;
                                background: white;
                            " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                                <option value="">Select a subject</option>
                                <option value="membership">Membership Information</option>
                                <option value="events">Event Information</option>
                                <option value="education">Educational Programs</option>
                                <option value="partnership">Partnership Opportunities</option>
                                <option value="media">Media Inquiry</option>
                                <option value="general">General Question</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="bcn-form-group">
                            <label for="message" style="
                                display: block;
                                font-weight: 500;
                                margin-bottom: 0.5rem;
                                color: #2c3e50;
                            ">Message *</label>
                            <textarea id="message" name="message" rows="6" required style="
                                width: 100%;
                                padding: 0.75rem;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                font-size: 1rem;
                                font-family: inherit;
                                resize: vertical;
                                transition: border-color 0.3s ease;
                            " onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'" placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        
                        <div class="bcn-form-group">
                            <label style="
                                display: flex;
                                align-items: center;
                                gap: 0.5rem;
                                font-size: 0.9rem;
                                color: #6c757d;
                                cursor: pointer;
                            ">
                                <input type="checkbox" name="newsletter" value="1" style="
                                    margin: 0;
                                ">
                                I'd like to receive updates about BCN events and educational opportunities
                            </label>
                        </div>
                        
                        <button type="submit" class="bcn-btn bcn-btn-primary" style="
                            background: #3498db;
                            color: white;
                            padding: 1rem 2rem;
                            border: none;
                            border-radius: 8px;
                            font-size: 1.125rem;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            justify-self: start;
                        " onmouseover="this.style.background='#2980b9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#3498db'; this.style.transform='translateY(0)'">
                            Send Message
                        </button>
                    </form>
                </div>
                
                <!-- Contact Information -->
                <div class="bcn-contact-info" style="
                    display: flex;
                    flex-direction: column;
                    gap: 2rem;
                ">
                    <!-- Contact Details -->
                    <div class="bcn-contact-details" style="
                        background: white;
                        padding: 3rem;
                        border-radius: 12px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    ">
                        <h3 style="
                            font-size: 1.5rem;
                            font-weight: 600;
                            margin: 0 0 2rem 0;
                            color: #2c3e50;
                        ">Get in Touch</h3>
                        
                        <div class="bcn-contact-item" style="
                            display: flex;
                            align-items: center;
                            gap: 1rem;
                            margin-bottom: 1.5rem;
                        ">
                            <div class="bcn-contact-icon" style="
                                width: 50px;
                                height: 50px;
                                background: #3498db;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                font-size: 1.25rem;
                            ">📧</div>
                            <div>
                                <h4 style="
                                    margin: 0 0 0.25rem 0;
                                    font-size: 1rem;
                                    font-weight: 600;
                                    color: #2c3e50;
                                ">Email</h4>
                                <p style="
                                    margin: 0;
                                    color: #6c757d;
                                ">info@buffalocannabisnetwork.com</p>
                            </div>
                        </div>
                        
                        <div class="bcn-contact-item" style="
                            display: flex;
                            align-items: center;
                            gap: 1rem;
                            margin-bottom: 1.5rem;
                        ">
                            <div class="bcn-contact-icon" style="
                                width: 50px;
                                height: 50px;
                                background: #27ae60;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                font-size: 1.25rem;
                            ">📱</div>
                            <div>
                                <h4 style="
                                    margin: 0 0 0.25rem 0;
                                    font-size: 1rem;
                                    font-weight: 600;
                                    color: #2c3e50;
                                ">Phone</h4>
                                <p style="
                                    margin: 0;
                                    color: #6c757d;
                                ">(716) 555-BCN1</p>
                            </div>
                        </div>
                        
                        <div class="bcn-contact-item" style="
                            display: flex;
                            align-items: center;
                            gap: 1rem;
                            margin-bottom: 1.5rem;
                        ">
                            <div class="bcn-contact-icon" style="
                                width: 50px;
                                height: 50px;
                                background: #e74c3c;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                font-size: 1.25rem;
                            ">📍</div>
                            <div>
                                <h4 style="
                                    margin: 0 0 0.25rem 0;
                                    font-size: 1rem;
                                    font-weight: 600;
                                    color: #2c3e50;
                                ">Location</h4>
                                <p style="
                                    margin: 0;
                                    color: #6c757d;
                                ">Buffalo, New York<br>Western New York Region</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="bcn-quick-links" style="
                        background: white;
                        padding: 3rem;
                        border-radius: 12px;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    ">
                        <h3 style="
                            font-size: 1.5rem;
                            font-weight: 600;
                            margin: 0 0 2rem 0;
                            color: #2c3e50;
                        ">Quick Links</h3>
                        
                        <div class="bcn-quick-link-list" style="
                            display: flex;
                            flex-direction: column;
                            gap: 1rem;
                        ">
                            <a href="/membership" style="
                                display: flex;
                                align-items: center;
                                gap: 1rem;
                                padding: 1rem;
                                background: #f8f9fa;
                                border-radius: 8px;
                                text-decoration: none;
                                color: #2c3e50;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.background='#e9ecef'; this.style.transform='translateX(5px)'" onmouseout="this.style.background='#f8f9fa'; this.style.transform='translateX(0)'">
                                <span style="font-size: 1.25rem;">👥</span>
                                <span>Membership Information</span>
                            </a>
                            
                            <a href="/events" style="
                                display: flex;
                                align-items: center;
                                gap: 1rem;
                                padding: 1rem;
                                background: #f8f9fa;
                                border-radius: 8px;
                                text-decoration: none;
                                color: #2c3e50;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.background='#e9ecef'; this.style.transform='translateX(5px)'" onmouseout="this.style.background='#f8f9fa'; this.style.transform='translateX(0)'">
                                <span style="font-size: 1.25rem;">📅</span>
                                <span>Upcoming Events</span>
                            </a>
                            
                            <a href="/about" style="
                                display: flex;
                                align-items: center;
                                gap: 1rem;
                                padding: 1rem;
                                background: #f8f9fa;
                                border-radius: 8px;
                                text-decoration: none;
                                color: #2c3e50;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.background='#e9ecef'; this.style.transform='translateX(5px)'" onmouseout="this.style.background='#f8f9fa'; this.style.transform='translateX(0)'">
                                <span style="font-size: 1.25rem;">ℹ️</span>
                                <span>About BCN</span>
                            </a>
                            
                            <a href="/resources" style="
                                display: flex;
                                align-items: center;
                                gap: 1rem;
                                padding: 1rem;
                                background: #f8f9fa;
                                border-radius: 8px;
                                text-decoration: none;
                                color: #2c3e50;
                                transition: all 0.3s ease;
                            " onmouseover="this.style.background='#e9ecef'; this.style.transform='translateX(5px)'" onmouseout="this.style.background='#f8f9fa'; this.style.transform='translateX(0)'">
                                <span style="font-size: 1.25rem;">📚</span>
                                <span>Educational Resources</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="bcn-contact-faq" style="
        padding: 4rem 0;
        background: white;
    ">
        <div class="container" style="
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        ">
            <h2 style="
                text-align: center;
                font-size: 2.5rem;
                font-weight: 700;
                margin: 0 0 3rem 0;
                color: #2c3e50;
            ">Frequently Asked Questions</h2>
            
            <div class="bcn-faq-grid" style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 2rem;
            ">
                <div class="bcn-faq-item" style="
                    background: #f8f9fa;
                    padding: 2rem;
                    border-radius: 12px;
                    border-left: 4px solid #3498db;
                ">
                    <h3 style="
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin: 0 0 1rem 0;
                        color: #2c3e50;
                    ">How do I become a BCN member?</h3>
                    <p style="
                        margin: 0;
                        color: #6c757d;
                        line-height: 1.6;
                    ">Membership is open to all cannabis industry professionals in Western New York. Simply fill out our membership application and pay the annual fee to join our network.</p>
                </div>
                
                <div class="bcn-faq-item" style="
                    background: #f8f9fa;
                    padding: 2rem;
                    border-radius: 12px;
                    border-left: 4px solid #27ae60;
                ">
                    <h3 style="
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin: 0 0 1rem 0;
                        color: #2c3e50;
                    ">What types of events does BCN host?</h3>
                    <p style="
                        margin: 0;
                        color: #6c757d;
                        line-height: 1.6;
                    ">We host networking mixers, educational workshops, panel discussions, policy updates, and industry conferences throughout the year.</p>
                </div>
                
                <div class="bcn-faq-item" style="
                    background: #f8f9fa;
                    padding: 2rem;
                    border-radius: 12px;
                    border-left: 4px solid #e74c3c;
                ">
                    <h3 style="
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin: 0 0 1rem 0;
                        color: #2c3e50;
                    ">Are there educational resources available?</h3>
                    <p style="
                        margin: 0;
                        color: #6c757d;
                        line-height: 1.6;
                    ">Yes! BCN provides access to compliance guides, marketing resources, business development tools, and expert-led training sessions.</p>
                </div>
                
                <div class="bcn-faq-item" style="
                    background: #f8f9fa;
                    padding: 2rem;
                    border-radius: 12px;
                    border-left: 4px solid #f39c12;
                ">
                    <h3 style="
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin: 0 0 1rem 0;
                        color: #2c3e50;
                    ">How can I get involved with BCN?</h3>
                    <p style="
                        margin: 0;
                        color: #6c757d;
                        line-height: 1.6;
                    ">Join as a member, attend events, volunteer for committees, or become a sponsor. We welcome all levels of involvement in our community.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Mobile Responsive Styles -->
<style>
@media (max-width: 768px) {
    .bcn-page-title {
        font-size: 2rem !important;
    }
    
    .bcn-page-intro {
        font-size: 1.125rem !important;
    }
    
    .bcn-contact-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    .bcn-form-row {
        grid-template-columns: 1fr !important;
    }
    
    .bcn-faq-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
// Contact form submission
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.bcn-contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Show loading state
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Thank you for your message! We\'ll get back to you soon.');
                    this.reset();
                } else {
                    alert('There was an error sending your message. Please try again.');
                }
            })
            .catch(error => {
                alert('There was an error sending your message. Please try again.');
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    }
});
</script>

<?php get_footer(); ?>
