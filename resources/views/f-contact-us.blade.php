<?php
$status = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $company = trim($_POST["company"] ?? "");
    $service = trim($_POST["service"] ?? "");
    $turnaround = trim($_POST["turnaround"] ?? "");
    $message = trim($_POST["message"] ?? "");
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $service && $message) {
        $record = [
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "company" => $company,
            "service" => $service,
            "turnaround" => $turnaround,
            "message" => $message,
            "date_sent" => date("Y-m-d H:i:s")
        ];
        $file = __DIR__ . "/contact_inquiries.json";
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($data)) $data = [];
        $data[] = $record;
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        @mail("hello@printify.co", "New Inquiry from $name", $message, "From: $email");
        $status = "success";
    } else {
        $status = "error";
    }
}
?>
<section id="contact" class="contact-section">
    <div class="contact-container">
        <div class="contact-head">
            <span>GET IN TOUCH</span>
            <h2>Contact <b>Us</b></h2>
            <p>We're here to help! Send us your inquiry and our team will assist you as soon as possible.</p>
        </div>
        <?php if ($status === "success"): ?>
            <div class="alert success">Your message has been sent successfully.</div>
        <?php elseif ($status === "error"): ?>
            <div class="alert error">Please complete all required fields properly.</div>
        <?php endif; ?>
        <div class="contact-grid">
            <div class="contact-card form-card">
                <div class="card-title">
                    <i class="fa-solid fa-paper-plane"></i>
                    <div>
                        <h3>Send Us a Message</h3>
                        <p>Fill out the form below and we'll get back to you soon.</p>
                    </div>
                </div>
                <form method="POST" id="contactForm">
                    <div class="form-fields">
                        <div class="two-col">
                            <div class="form-row">
                                <label>Full Name *</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-user"></i>
                                    <input type="text" name="name" placeholder="Enter your full name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Email Address *</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input type="email" name="email" placeholder="Enter your email address" required>
                                </div>
                            </div>
                        </div>
                        <div class="two-col">
                            <div class="form-row">
                                <label>Phone Number</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-phone"></i>
                                    <input type="tel" name="phone" placeholder="Enter your phone number">
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Company Optional</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-briefcase"></i>
                                    <input type="text" name="company" placeholder="Enter your company name">
                                </div>
                            </div>
                        </div>
                        <div class="two-col">
                            <div class="form-row">
                                <label>Service Interested In *</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-list-ul"></i>
                                    <select name="service" required>
                                        <option value="">Select a service</option>
                                        <option>Business Cards</option>
                                        <option>Tarpaulin Printing</option>
                                        <option>Stickers & Labels</option>
                                        <option>Invitation Printing</option>
                                        <option>Custom Design</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Preferred Turnaround</label>
                                <div class="input-wrapper">
                                    <i class="fa-solid fa-clock"></i>
                                    <select name="turnaround">
                                        <option value="">Select timeframe</option>
                                        <option>Rush Order</option>
                                        <option>1-2 Business Days</option>
                                        <option>3-5 Business Days</option>
                                        <option>Flexible</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row message-row">
                            <label>Message *</label>
                            <div class="input-wrapper textarea-wrapper">
                                <i class="fa-solid fa-pencil"></i>
                                <textarea name="message" placeholder="Tell us about your project..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-bottom">
                        <small><i class="fa-solid fa-lock"></i> We respect your privacy.</small>
                        <button type="submit"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
                    </div>
                </form>
            </div>
            <div class="right-side">
                <div class="contact-card info-card">
                    <div class="touch-box">
                        <h3><i class="fa-solid fa-headset"></i> Get In Touch</h3>
                        <p><i class="fa-solid fa-phone"></i><b>Call Us</b><br><span>+63 912 345 6789</span><br><small>Mon-Fri, 8:00 AM - 6:00 PM</small></p>
                        <p><i class="fa-solid fa-envelope"></i><b>Email Us</b><br><span>hello@printify.co</span><br><small>We reply within 1 business day</small></p>
                        <p><i class="fa-solid fa-message"></i><b>Live Chat</b><br><span>Available on website</span><br><small>Mon-Fri, 8:00 AM - 6:00 PM</small></p>
                        <p><i class="fa-solid fa-location-dot"></i><b>Visit Us</b><br><span>123 Printify Avenue, Makati City, Metro Manila</span></p>
                    </div>
                    <div class="hours-box">
                        <h3><i class="fa-regular fa-clock"></i> Office Hours</h3>
                        <ul>
                            <li><span>Monday - Friday</span><b>8:00 AM - 6:00 PM</b></li>
                            <li><span>Saturday</span><b>9:00 AM - 3:00 PM</b></li>
                            <li><span>Sunday</span><b>Closed</b></li>
                            <li><span>Holidays</span><b>Closed</b></li>
                        </ul>
                        <h3><i class="fa-solid fa-share-nodes"></i> Follow Us</h3>
                        <div class="socials">
                            <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#"><i class="fa-brands fa-youtube"></i></a>
                            <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="contact-card branch-card">
                    <h3><i class="fa-solid fa-map-pin"></i> Our Branches</h3>
                    <div class="branch-content">
                        <div class="branch-list">
                            <div class="branch-item">
                                <b>Makati Branch</b>
                                <span>123 Printify Avenue</span>
                                <small>Main Office</small>
                                <p><i class="fa-solid fa-clock"></i> Open Mon-Sat</p>
                                <p><i class="fa-solid fa-print"></i> Printing, design, pickup</p>
                            </div>
                            <div class="branch-item">
                                <b>Quezon City Branch</b>
                                <span>45 Timog Avenue</span>
                                <small>Branch Office</small>
                                <p><i class="fa-solid fa-clock"></i> Open Mon-Sat</p>
                                <p><i class="fa-solid fa-box"></i> Orders, pickup, inquiries</p>
                            </div>
                        </div>
                        <div class="branch-notes">
                            <div>
                                <p><i class="fa-solid fa-circle-check"></i> Same-day assistance available for selected services.</p>
                                <p><i class="fa-solid fa-headset"></i> Contact us before visiting for bulk and rush orders.</p>
                            </div>
                            <button onclick="openMap()">Get Directions <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="contact-extra">
                <div class="contact-card quick-answer-card">
                    <h3><i class="fa-solid fa-circle-question"></i> Quick Answers</h3>
                    <button type="button" onclick="contactQuickAnswer('Turnaround Time')"><span><i class="fa-solid fa-truck-fast"></i></span><b>Turnaround Time</b><small>Production and delivery info.</small><i class="fa-solid fa-chevron-right"></i></button>
                    <button type="button" onclick="contactQuickAnswer('Request a Quote')"><span><i class="fa-solid fa-file-lines"></i></span><b>Request a Quote</b><small>Fast and free estimate.</small><i class="fa-solid fa-chevron-right"></i></button>
                    <button type="button" onclick="contactQuickAnswer('File Guide')"><span><i class="fa-solid fa-cube"></i></span><b>File & Design Guide</b><small>Prepare files the right way.</small><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="contact-card map-card">
                    <div>
                        <h3><i class="fa-solid fa-map-location-dot"></i> Get Directions</h3>
                        <p>Find us easily. Open in your favorite map app.</p>
                        <button type="button" onclick="openMap()">Open in Maps</button>
                    </div>
                    <div class="mini-map" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></div>
                </div>
                <div class="reply-card">
                    <div class="reply-icon"><i class="fa-solid fa-stopwatch"></i></div>
                    <div><h3>We reply within one business day.</h3><p>Your project matters to us. Let's bring your ideas to life.</p></div>
                    <button type="button" onclick="focusContactMessage()"><i class="fa-solid fa-paper-plane"></i> Send Your Inquiry</button>
                </div>
            </aside>
        </div>
    </div>
</section>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.contact-section{padding:24px 35px 10px clamp(35px,7vw,92px);background:#fff;font-family:Inter,Poppins,Arial,sans-serif;color:#111}
.contact-container{width:100%;max-width:1490px;margin:0}
.contact-head{margin-bottom:18px}
.contact-head span{color:#ff4b00;font-weight:800;font-size:12px;letter-spacing:1px}
.contact-head h2{font-family:'Playfair Display',serif;font-size:45px;margin:5px 0 8px;font-weight:700;text-transform:uppercase;line-height:1;letter-spacing:0}
.contact-head h2 b,.info-card h3 i,.branch-card h3 i,.info-card p i,.branch-notes i{color:#ff4b00}
.contact-head p{max-width:620px;color:#555;font-size:14px;line-height:1.5;margin:0}
.alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-weight:700;font-size:13px}
.success{background:#e7fff0;color:#168047}.error{background:#ffecec;color:#bd1e1e}
.contact-grid{display:grid;grid-template-columns:420px 680px 300px;gap:30px;align-items:stretch}
.contact-card{background:#fff;border:1px solid #111827;border-radius:15px;box-shadow:0 8px 24px rgba(0,0,0,.055);box-sizing:border-box}
.form-card{padding:14px 15px 13px;min-height:100%;height:100%;display:flex;flex-direction:column}
.form-card form{flex:1;min-height:0;display:flex;flex-direction:column}
.form-fields{flex:1;min-height:0;display:flex;flex-direction:column;gap:7px}
.card-title{display:flex;gap:12px;align-items:center;margin-bottom:10px}
.card-title i{width:36px;height:36px;display:grid;place-items:center;background:#fff1eb;color:#ff4b00;border-radius:50%;font-size:15px;flex-shrink:0}
.card-title h3,.info-card h3,.branch-card h3,.quick-answer-card h3,.map-card h3,.reply-card h3{margin:0 0 5px;font-family:Poppins,sans-serif;font-size:20px;font-weight:600;line-height:1.1;letter-spacing:0}
.card-title p{margin:0;color:#777;font-size:12px}
.form-row{display:flex;flex-direction:column;margin-bottom:0}
.form-row label{font-size:11px;font-weight:800;margin-bottom:4px;color:#444}
.input-wrapper{position:relative;display:flex;align-items:center}
.textarea-wrapper{align-items: flex-start;}
.input-wrapper i{position:absolute;left:12px;color:#aaa;font-size:14px}
.textarea-wrapper i{top:12px;}
input,select,textarea{width:100%;height:40px;border:1px solid #dedede;border-radius:8px;padding:9px 10px 9px 35px;font-size:11px;outline:none;background:#fff;box-sizing:border-box}
textarea{resize:none;min-height:82px;height:100%;padding:9px 10px 9px 35px}
.message-row{flex:1;min-height:94px}.message-row textarea{flex:1}
input:focus,select:focus,textarea:focus{border-color:#ff4b00;box-shadow:0 0 0 3px rgba(255,75,0,.1)}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.form-bottom{margin-top:9px;display:flex;align-items:center;justify-content:space-between;gap:12px}
.form-bottom small{color:#777;font-size:11px}
.form-bottom button,.branch-card button,.map-card button,.reply-card button{width:132px;height:34px;border:1px solid #ff7a00;background:#ff7a00;color:#000;border-radius:8px;padding:0 10px;font-weight:900;font-size:10px;text-transform:uppercase;cursor:pointer;transition:background-color .2s ease,color .2s ease,border-color .2s ease;flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;gap:5px;white-space:nowrap}
.form-bottom button:hover,.branch-card button:hover,.map-card button:hover,.reply-card button:hover{background:#111827;border-color:#111827;color:#fff}
.right-side{width:680px;display:flex;flex-direction:column;gap:12px;height:100%}
.info-card{width:600px;padding:17px 20px;display:grid;grid-template-columns:300px 300px;gap:20px;align-items:start}
.touch-box{padding-right:14px;border-right:1px solid #eee}.hours-box{padding-left:2px}
.info-card h3 i,.branch-card h3 i{margin-right:7px}
.info-card p{line-height:1.26;color:#333;font-size:12px;margin:10px 0;padding-left:24px;position:relative}
.info-card p i{position:absolute;left:0;top:3px}
.info-card p b,.info-card p span,.info-card small{display:inline-block}
.info-card small{color:#777;font-size:11px}
.info-card ul{list-style:none;padding:0;margin:12px 0 18px}
.info-card li{display:flex;justify-content:space-between;align-items:center;gap:12px;border-bottom:1px solid #eee;padding:9px 0;font-size:12px}
.info-card li span{flex:0 0 125px}.info-card li b{white-space:nowrap}
.socials{display:flex;gap:11px}
.socials a{width:36px;height:36px;border-radius:50%;display:grid;place-items:center;color:#fff;text-decoration:none;font-size:14px}
.socials a:nth-child(1){background:#1877f2}.socials a:nth-child(2){background:#e4405f}.socials a:nth-child(3){background:#ff0000}.socials a:nth-child(4){background:#0a66c2}
.branch-card{width:600px;padding:14px 20px 15px;min-height:145px}
.branch-content{display:grid;grid-template-columns:330px 1fr;gap:20px;align-items:stretch;margin:9px 0 0}
.branch-list{display:grid;grid-template-columns:145px 155px;gap:20px}
.branch-list b,.branch-list span,.branch-list small{display:block}
.branch-list b{font-size:12px}
.branch-list span{color:#666;margin:5px 0;font-size:11px}
.branch-list small{color:#0b73e8;font-weight:800;font-size:11px;margin-bottom:5px}
.branch-item p{margin:4px 0;color:#555;font-size:11px;line-height:1.25}
.branch-item p i{color:#ff4b00;margin-right:5px;width:11px}
.branch-notes{display:flex;flex-direction:column;justify-content:space-between;align-items:flex-start;min-height:94px;padding-top:10px}
.branch-notes p{margin:0 0 11px;color:#555;font-size:12px;line-height:1.35;font-weight:600}
.branch-notes i{margin-right:6px;font-size:11px}
.branch-card button i{margin:0;font-size:11px}
.branch-card button{align-self:flex-end;margin-top:3px}
.contact-extra{display:flex;flex-direction:column;gap:12px;width:450px}
.quick-answer-card{padding:13px}.quick-answer-card h3{font-size:15px;margin-bottom:10px}.quick-answer-card h3 i,.map-card h3 i{color:#ff4b00;margin-right:7px}.quick-answer-card button{width:100%;min-height:54px;border:1px solid #e5e7eb;background:#fff;border-radius:10px;display:grid;grid-template-columns:34px minmax(0,1fr) 12px;gap:9px;align-items:center;text-align:left;margin-bottom:8px;padding:8px;cursor:pointer;transition:.18s}.quick-answer-card button:hover{border-color:#111827;background:#fff7ed}.quick-answer-card button span{width:32px;height:32px;border-radius:9px;background:#fff1e8;color:#ff4b00;display:grid;place-items:center}.quick-answer-card button b{display:block;font-size:11px}.quick-answer-card button small{display:block;color:#666;font-size:9.5px;line-height:1.25}
.map-card{padding:13px;display:grid;grid-template-columns:minmax(0,1fr) 112px;gap:10px;align-items:center}.map-card p{margin:0 0 8px;color:#666;font-size:10.5px;line-height:1.35}.map-card h3{font-size:14px}.map-card button{width:112px;height:32px}.mini-map{height:82px;border-radius:11px;background:linear-gradient(135deg,#f3f4f6,#fff1e8);position:relative;overflow:hidden;border:1px solid #e5e7eb}.mini-map:before,.mini-map:after{content:'';position:absolute;background:#d1d5db;border-radius:999px}.mini-map:before{width:150px;height:10px;left:-18px;top:29px;transform:rotate(-18deg)}.mini-map:after{width:130px;height:9px;left:12px;bottom:20px;transform:rotate(24deg)}.mini-map i{position:absolute;right:30px;top:17px;font-size:30px;color:#ff4b00;z-index:2}
.reply-card{min-height:92px;border:1px solid #111827;border-radius:15px;background:#fff4ed;padding:14px;display:grid;grid-template-columns:46px minmax(0,1fr);gap:12px;align-items:center}.reply-icon{width:46px;height:46px;border-radius:50%;background:#ff7a00;color:#fff;display:grid;place-items:center;font-size:20px}.reply-card h3{font-size:15px;margin:0 0 4px}.reply-card p{margin:0;color:#555;font-size:10.8px;line-height:1.35}.reply-card button{grid-column:1/-1;width:100%;height:34px;margin-top:2px}
.contact-grid{grid-template-columns:390px 680px minmax(340px,360px);gap:24px;align-items:start}
.contact-card:hover{background:#fff7f2;box-shadow:0 18px 38px rgba(255,90,18,.08)}
.form-card{height:auto;min-height:435px;padding:12px 13px 11px}
.form-fields{gap:6px}
.card-title{margin-bottom:8px}
input,select{height:36px}
textarea{min-height:68px}
.message-row{min-height:78px}
.form-bottom{margin-top:7px}
.right-side{gap:10px}
.info-card{padding:14px 17px;grid-template-columns:300px 300px}
.info-card p{margin:8px 0;font-size:11.5px;line-height:1.2}
.info-card ul{margin:9px 0 13px}
.info-card li{padding:7px 0;font-size:11.5px}
.branch-card{padding:12px 17px 12px;min-height:128px}
.branch-content{margin-top:7px;gap:15px}
.branch-notes{min-height:78px;padding-top:4px}
.branch-notes p{margin-bottom:8px;font-size:11.2px}
.contact-extra{width:100%;gap:8px}
.quick-answer-card{padding:10px 12px}  min-height:80px;
.quick-answer-card h3{font-size:14px;margin-bottom:7px}
.quick-answer-card button{min-height:38px;border:0;border-bottom:1px solid #e5e7eb;border-radius:0;background:transparent;grid-template-columns:28px minmax(0,1fr) 12px;gap:7px;margin-bottom:0;padding:6px 0}
.quick-answer-card button:last-child{border-bottom:0}
.quick-answer-card button:hover{border-color:#111827;background:#fff1e8}
.quick-answer-card button span{width:26px;height:26px;border-radius:7px}
.quick-answer-card button b{font-size:10.5px}
.quick-answer-card button small{font-size:8.6px;line-height:1.15}
.map-card{padding:10px 12px;grid-template-columns:minmax(0,1fr) 100px;gap:8px}
.map-card p{font-size:9.8px;margin-bottom:6px}
.map-card button{width:105px;height:30px}
.mini-map{height:66px}
.mini-map i{right:27px;top:13px;font-size:25px}
.reply-card{min-height:76px;padding:10px 12px;grid-template-columns:36px minmax(0,1fr);gap:9px}
.reply-icon{width:36px;height:36px;font-size:16px}
.reply-card h3{font-size:13.5px}
.reply-card p{font-size:9.8px}
.reply-card button{height:30px;margin-top:0}
@media(max-width:1180px){
.contact-section{padding:25px 35px 45px}.contact-container{max-width:100%;margin:0 auto}.contact-grid{grid-template-columns:340px 1fr;gap:25px}.contact-extra{grid-column:1/-1;width:100%;display:grid;grid-template-columns:1fr 1fr 1fr}.right-side,.info-card,.branch-card{width:100%}.info-card{grid-template-columns:1fr 1fr}.branch-content{grid-template-columns:1fr}.branch-list{grid-template-columns:1fr 1fr}.branch-notes{padding-top:0;min-height:auto}
}
@media(max-width:900px){
.contact-grid,.contact-extra{grid-template-columns:1fr}.right-side,.info-card,.branch-card{width:100%}.form-card{min-height:auto;height:auto}
}
@media(max-width:700px){
.contact-section{padding:22px 18px 35px}.contact-head h2{font-size:36px}.two-col,.info-card,.branch-list{grid-template-columns:1fr}.touch-box{padding-right:0;border-right:0;border-bottom:1px solid #eee;padding-bottom:15px}.hours-box{padding-left:0}.form-bottom{align-items:flex-start;flex-direction:column}.form-bottom button{align-self:flex-end}.branch-card button{align-self:flex-start}
}
</style>
<script>
const form = document.getElementById("contactForm");
if (form) {
    form.addEventListener("submit", function(e) {
        const requiredFields = form.querySelectorAll("[required]");
        let valid = true;
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = "#d60000";
                valid = false;
            } else {
                field.style.borderColor = "#dedede";
            }
        });
        if (!valid) {
            e.preventDefault();
            alert("Please fill out all required fields.");
        }
    });
}
function openMap() {
    window.open("https://www.google.com/maps/search/123+Printify+Avenue+Makati+City", "_blank");
}
function contactQuickAnswer(topic){
    const service=document.querySelector('select[name="service"]');
    const turnaround=document.querySelector('select[name="turnaround"]');
    const message=document.querySelector('textarea[name="message"]');
    if(topic==='Turnaround Time'&&turnaround)turnaround.value='Rush Order';
    if(topic==='Request a Quote'&&service)service.value='Custom Design';
    if(topic==='File Guide'&&service)service.value='Custom Design';
    if(message){message.value=`Hi Printify, I need help with ${topic}.`;message.focus();}
    window.dispatchEvent(new CustomEvent('printify-front-feedback',{detail:{message:`${topic} selected.`}}));
}
function focusContactMessage(){
    const message=document.querySelector('textarea[name="message"]');
    if(message){message.focus();message.scrollIntoView({behavior:'smooth',block:'center'});}
}
</script>
