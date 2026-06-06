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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Playfair+Display:wght@700&family=Poppins:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<section id="contact" class="contact-section">
    <div id="contactToast" class="contact-toast" role="status" aria-live="polite"></div>

    <div class="contact-container">
        <div class="contact-head">
            <span>Get In Touch</span>
            <h2>Contact <b>Us</b></h2>
            <p>We're here to help! Send us your inquiry and our team will assist you as soon as possible.</p>
        </div>

        <?php if ($status === "success"): ?>
            <div class="alert success">Your message has been sent successfully.</div>
        <?php elseif ($status === "error"): ?>
            <div class="alert error">Please complete all required fields properly.</div>
        <?php endif; ?>

        <div class="contact-grid">
            <div class="contact-card main-box form-card">
                <div class="card-title">
                    <i class="fa-solid fa-paper-plane icon-orange"></i>
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
                        <button class="ui-btn orange-btn" type="submit">
                            <i class="fa-solid fa-paper-plane"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <div class="middle-side">
                <div class="info-card no-box">
                    <div class="touch-box">
                        <h3><i class="fa-solid fa-headset icon-orange"></i> Get In Touch</h3>

                        <p>
                            <i class="fa-solid fa-phone icon-green"></i>
                            <b>Call Us</b>
                            <span>+63 912 345 6789</span>
                            <small>Mon-Fri, 8:00 AM - 6:00 PM</small>
                        </p>

                        <p>
                            <i class="fa-solid fa-envelope icon-black"></i>
                            <b>Email Us</b>
                            <span>hello@printify.co</span>
                        </p>

                        <p>
                            <i class="fa-solid fa-message icon-black"></i>
                            <b>Live Chat</b>
                            <span>Available on website</span>
                            <small>Mon-Fri, 8:00 AM - 6:00 PM</small>
                        </p>

                        <p>
                            <i class="fa-solid fa-location-dot icon-red"></i>
                            <b>Visit Us</b>
                            <span>123 Printify Avenue, Makati City, Metro Manila</span>
                        </p>
                    </div>

                    <div class="hours-box">
                        <h3><i class="fa-regular fa-clock icon-orange"></i> Office Hours</h3>
                        <ul>
                            <li><span>Monday - Friday</span><b>8:00 AM - 6:00 PM</b></li>
                            <li><span>Saturday</span><b>9:00 AM - 3:00 PM</b></li>
                            <li><span>Sunday</span><b>Closed</b></li>
                            <li><span>Holidays</span><b>Closed</b></li>
                        </ul>

                        <h3><i class="fa-solid fa-share-nodes icon-blue"></i> Follow Us</h3>
                        <div class="socials">
                            <a href="https://facebook.com" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="https://instagram.com" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                            <a href="https://youtube.com" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                            <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

                <div class="contact-card main-box branch-card">
                    <h3><i class="fa-solid fa-map-pin icon-orange"></i> Our Branches</h3>
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
                                <p><i class="fa-regular fa-clock icon-blue"></i> Same-day assistance available for selected services.</p>
                                <p><i class="fa-solid fa-phone icon-purple"></i> Contact us before visiting for bulk and rush orders.</p>
                            </div>
                            <button class="ui-btn black-btn" type="button" onclick="openMap()">
                                Get Directions
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="contact-extra">
                <div class="quick-answer-card no-box">
                    <h3><i class="fa-solid fa-circle-question icon-orange"></i> Quick Answers</h3>

                    <button type="button" onclick="contactQuickAnswer('Turnaround Time')">
                        <span><i class="fa-solid fa-truck-fast icon-blue"></i></span>
                        <b>Turnaround Time</b>
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>

                    <button type="button" onclick="contactQuickAnswer('Request a Quote')">
                        <span><i class="fa-solid fa-file-lines icon-green"></i></span>
                        <b>Request a Quote</b>
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>

                    <button type="button" onclick="contactQuickAnswer('File Guide')">
                        <span><i class="fa-solid fa-cube icon-purple"></i></span>
                        <b>File & Design Guide</b>
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <div class="map-card no-box">
                    <div class="map-head">
                        <h3><i class="fa-solid fa-map-location-dot icon-orange"></i> Get Directions</h3>
                        <p>Find us easily. Open in your favorite map app.</p>
                        <button class="ui-btn orange-btn" type="button" onclick="openMap()">
                            Open in Maps
                            <i class="fa-solid fa-up-right-from-square"></i>
                        </button>
                    </div>

                    <div class="map-frame">
                        <iframe title="Printify & Co. location map" src="https://www.google.com/maps?q=Makati%20City%20Metro%20Manila%20Philippines&output=embed" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<style>
:root {
    --orange: #ff6a00;
    --black: #111111;
    --text: #151515;
    --muted: #515761;
    --line: #e2e6ee;
}

* { box-sizing: border-box; }

.contact-section {
    width: 100%;
    background: #ffffff;
    color: var(--text);
    padding: 42px 18px 70px 100px;
    font-family: "Inter", Arial, sans-serif;
    font-weight: 400;
    letter-spacing: 0;
}

.contact-container {
    width: 100%;
    max-width: 1450px;
    margin: 0;
}

.contact-head { margin-bottom: 20px; }

.contact-head span {
    display: block;
    color: var(--orange);
    font-family: "Poppins", Arial, sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.contact-head h2 {
    margin: 0 0 8px;
    color: #000;
    font-family: "Playfair Display", Georgia, serif;
    font-size: 48px;
    line-height: .96;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0;
}

.contact-head h2 b { color: var(--orange); font-weight: 700; }

.contact-head p,
.card-title p,
.map-head p,
.form-bottom small,
.branch-notes p,
.branch-item p,
.branch-list span,
.info-card p,
.info-card p small,
.info-card li {
    font-family: "Inter", Arial, sans-serif;
    font-weight: 400;
    letter-spacing: 0;
}

.contact-head p {
    margin: 0;
    color: #303743;
    font-size: 14px;
    line-height: 1.45;
}

.alert {
    width: fit-content;
    padding: 10px 14px;
    border-radius: 10px;
    margin: 0 0 18px;
    font-family: "Poppins", Arial, sans-serif;
    font-size: 12px;
    font-weight: 600;
}

.alert.success { background: #e9fff0; color: #157a3f; border: 1px solid #b8f3ca; }
.alert.error { background: #fff0f0; color: #b42318; border: 1px solid #ffd1d1; }

.contact-toast {
    position: fixed;
    top: 94px;
    left: 50%;
    transform: translate(-50%, -15px);
    z-index: 9999;
    min-width: 280px;
    max-width: 440px;
    padding: 13px 22px;
    border-radius: 18px;
    background: #111827;
    color: #ffffff;
    text-align: center;
    font-family: "Inter", Arial, sans-serif;
    font-size: 13px;
    line-height: 1.35;
    opacity: 0;
    pointer-events: none;
    box-shadow: 0 18px 40px rgba(0, 0, 0, .22);
    transition: opacity .2s ease, transform .2s ease;
}

.contact-toast.show { opacity: 1; transform: translate(-50%, 0); }

.contact-grid {
    display: grid;
    grid-template-columns: 420px 545px 360px;
    gap: 22px;
    align-items: stretch;
}

.contact-card,
.no-box {
    background: #ffffff;
    border-radius: 10px;
}

.main-box {
    border: 1px solid #000000;
    box-shadow: none;
}

.form-card,
.middle-side,
.contact-extra { min-height: 565px; }

.form-card {
    padding: 20px 15px 18px;
    display: flex;
    flex-direction: column;
}

.form-card form {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 22px;
}

.card-title > i {
    width: 44px;
    height: 44px;
    display: grid;
    place-items: center;
    font-size: 25px;
    flex: 0 0 auto;
}

.card-title h3,
.info-card h3,
.branch-card h3,
.quick-answer-card h3,
.map-card h3 {
    margin: 0;
    color: #151515;
    font-family: "Poppins", Arial, sans-serif;
    font-weight: 600;
    letter-spacing: 0;
}

.card-title h3 { font-size: 19px; margin-bottom: 5px; }
.card-title p { margin: 0; color: #303743; font-size: 12px; line-height: 1.4; }

.form-fields { display: flex; flex-direction: column; gap: 18px; flex: 1; }
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.form-row { display: flex; flex-direction: column; }

.form-row label {
    font-family: "Poppins", Arial, sans-serif;
    font-size: 11px;
    color: #181818;
    font-weight: 600;
    margin-bottom: 8px;
    letter-spacing: 0;
}

.input-wrapper { position: relative; display: flex; align-items: center; }
.input-wrapper i { position: absolute; left: 12px; color: #1b1b1b; font-size: 15px; pointer-events: none; z-index: 2; }

.input-wrapper input,
.input-wrapper select,
.input-wrapper textarea {
    width: 100%;
    height: 43px;
    border: 1px solid #d9dee7;
    border-radius: 8px;
    background: #ffffff;
    color: #111827;
    font-family: "Inter", Arial, sans-serif;
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 0;
    outline: none;
    padding: 10px 12px 10px 40px;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.input-wrapper select { cursor: pointer; appearance: auto; }
.textarea-wrapper { align-items: flex-start; }
.textarea-wrapper i { top: 14px; }
.input-wrapper textarea { height: 130px; min-height: 130px; resize: none; line-height: 1.45; padding-top: 14px; }
.message-row { flex: 1; }

.input-wrapper input:focus,
.input-wrapper select:focus,
.input-wrapper textarea:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(255, 106, 0, .11);
}

.form-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-top: 21px;
}

.form-bottom small { display: inline-flex; align-items: center; gap: 8px; color: #575b63; font-size: 12px; }
.form-bottom small i { color: #1a1a1a; }

.ui-btn {
    width: 160px;
    height: 40px;
    border: 0;
    border-radius: 8px;
    cursor: pointer;
    font-family: "Poppins", Arial, sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    white-space: nowrap;
    transition: transform .18s ease, background .18s ease, color .18s ease;
}

.orange-btn { background: var(--orange); color: #000000; }
.black-btn { background: #111827; color: #ffffff; }
.ui-btn:hover { background: #111827; color: #ffffff; transform: translateY(-1px); }
.black-btn:hover { background: var(--orange); color: #000000; }

.middle-side { display: flex; flex-direction: column; gap: 20px; }

.info-card {
    height: 342px;
    padding: 16px 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.touch-box { padding: 0 17px 0 0; border-right: 1px solid var(--line); }
.hours-box { padding: 0 0 0 13px; }

.info-card h3 {
    font-size: 18px;
    line-height: 1.2;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 9px;
}

.info-card h3 i { font-size: 22px; }

.info-card p {
    position: relative;
    margin: 0 0 14px;
    padding-left: 37px;
    font-size: 12px;
    color: #1f2937;
    line-height: 1.3;
}

.info-card p > i { position: absolute; left: 0; top: 2px; width: 24px; text-align: center; font-size: 19px; }
.info-card p b,
.info-card p span,
.info-card p small { display: block; }
.info-card p b { color: #121212; font-family: "Poppins", Arial, sans-serif; font-weight: 600; margin-bottom: 3px; }
.info-card p span { color: #1f2937; }
.info-card p small { color: #202020; font-size: 11px; }

.info-card ul { list-style: none; margin: 0 0 14px; padding: 0; }
.info-card li { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--line); color: #1f2937; font-size: 11.5px; }
.info-card li:first-child { padding-top: 4px; }
.info-card li span { white-space: nowrap; }
.info-card li b { color: #111827; font-family: "Poppins", Arial, sans-serif; font-weight: 600; white-space: nowrap; }

.socials { display: flex; align-items: center; gap: 10px; }
.socials a { width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; text-decoration: none; color: #fff; font-size: 17px; transition: transform .18s ease; }
.socials a:hover { transform: translateY(-2px); }
.socials a:nth-child(1) { background: #1877f2; }
.socials a:nth-child(2) { background: #e4405f; }
.socials a:nth-child(3) { background: #ff0000; }
.socials a:nth-child(4) { background: #0a66c2; }

.branch-card { flex: 1; min-height: 203px; padding: 18px 19px; }
.branch-card h3 { font-size: 18px; display: flex; align-items: center; gap: 10px; }
.branch-card h3 i { font-size: 26px; }
.branch-content { display: grid; grid-template-columns: 1fr 190px; gap: 20px; margin-top: 17px; }
.branch-list { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.branch-list b,
.branch-list span,
.branch-list small { display: block; }
.branch-list b { color: #111827; font-family: "Poppins", Arial, sans-serif; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
.branch-list span { color: #303743; font-size: 11px; margin-bottom: 8px; }
.branch-list small { width: fit-content; color: #0b73e8; font-family: "Poppins", Arial, sans-serif; font-size: 10px; font-weight: 600; margin-bottom: 10px; }
.branch-item p { margin: 0 0 7px; color: #303743; font-size: 11px; line-height: 1.25; display: flex; align-items: center; gap: 7px; }
.branch-item p i { color: var(--orange); width: 14px; text-align: center; }
.branch-notes { display: flex; flex-direction: column; justify-content: space-between; min-height: 140px; }
.branch-notes p { margin: 0 0 15px; color: #303743; font-size: 11.5px; line-height: 1.42; display: flex; align-items: flex-start; gap: 9px; }
.branch-notes p i { margin-top: 2px; width: 16px; text-align: center; flex: 0 0 auto; }
.branch-card button { align-self: flex-end; }

.contact-extra { display: flex; flex-direction: column; gap: 20px; }
.quick-answer-card { height: 213px; padding: 20px 0 16px; }
.quick-answer-card h3 { font-size: 16px; margin-bottom: 17px; display: flex; align-items: center; gap: 10px; }
.quick-answer-card h3 i { font-size: 24px; }

.quick-answer-card button {
    width: 100%;
    height: 53px;
    border: 0;
    border-bottom: 1px solid var(--line);
    background: transparent;
    display: grid;
    grid-template-columns: 34px 1fr 14px;
    align-items: center;
    gap: 11px;
    text-align: left;
    cursor: pointer;
    padding: 0 8px;
    color: #111827;
    border-radius: 0;
    transition: background .18s ease, color .18s ease;
}

.quick-answer-card button:last-child { border-bottom: 0; }
.quick-answer-card button span { width: 28px; height: 28px; display: grid; place-items: center; border-radius: 8px; }
.quick-answer-card button span i { font-size: 20px; }
.quick-answer-card button b { color: inherit; font-family: "Poppins", Arial, sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 0; }
.quick-answer-card button > i { color: inherit; font-size: 13px; }
.quick-answer-card button:hover { background: #111827; color: #ffffff; border-bottom-color: #111827; }
.quick-answer-card button:hover i { color: #ffffff !important; }

.map-card { flex: 1; min-height: 332px; padding: 18px 0 0; display: flex; flex-direction: column; }
.map-head h3 { font-size: 18px; display: flex; align-items: center; gap: 10px; margin-bottom: 9px; }
.map-head h3 i { font-size: 27px; }
.map-head p { margin: 0 0 13px; color: var(--muted); font-size: 12px; line-height: 1.4; }
.map-card button { margin-bottom: 18px; }

.map-frame {
    width: 100%;
    flex: 1;
    min-height: 184px;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #dde3ec;
    background: #edf2f7;
}

.map-frame iframe { width: 100%; height: 100%; border: 0; display: block; pointer-events: auto; touch-action: auto; }

.icon-orange { color: var(--orange) !important; }
.icon-black { color: #111111 !important; }
.icon-green { color: #22c55e !important; }
.icon-red { color: #ef233c !important; }
.icon-blue { color: #1888ff !important; }
.icon-purple { color: #7c3aed !important; }

@media (max-width: 1320px) {
    .contact-section { padding-left: 40px; padding-right: 30px; }
    .contact-grid { grid-template-columns: 400px 1fr 340px; gap: 18px; }
}

@media (max-width: 1180px) {
    .contact-section { padding: 32px 22px 55px; }
    .contact-container { max-width: 100%; margin: 0 auto; }
    .contact-grid { grid-template-columns: 1fr; }
    .form-card,
    .middle-side,
    .contact-extra { min-height: auto; }
    .info-card { height: auto; min-height: 320px; }
    .branch-card { min-height: auto; }
    .contact-extra { display: grid; grid-template-columns: 1fr 1fr; align-items: stretch; }
    .quick-answer-card,
    .map-card { height: auto; min-height: 260px; }
}

@media (max-width: 760px) {
    .contact-section { padding: 25px 14px 45px; }
    .contact-head h2 { font-size: 38px; }
    .contact-grid { gap: 18px; }
    .two-col,
    .info-card,
    .branch-content,
    .branch-list,
    .contact-extra { grid-template-columns: 1fr; }
    .info-card { display: grid; }
    .touch-box { padding-right: 0; border-right: 0; border-bottom: 1px solid var(--line); padding-bottom: 16px; }
    .hours-box { padding-left: 0; }
    .branch-card button { align-self: flex-start; }
    .form-bottom { align-items: flex-start; flex-direction: column; }
    .form-bottom button { align-self: flex-end; }
    .map-frame { min-height: 220px; }
}

</style>

<script>
const contactToast = document.getElementById("contactToast");
const contactForm = document.getElementById("contactForm");
let contactToastTimer;

function showContactFeedback(message) {
    if (!contactToast) return;
    contactToast.textContent = message;
    contactToast.classList.add("show");
    clearTimeout(contactToastTimer);
    contactToastTimer = setTimeout(() => contactToast.classList.remove("show"), 2600);
}

window.addEventListener("printify-front-feedback", event => {
    showContactFeedback(event.detail?.message || "Action completed.");
});

if (contactForm) {
    contactForm.addEventListener("submit", function(e) {
        const requiredFields = contactForm.querySelectorAll("[required]");
        let valid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = "#d60000";
                valid = false;
            } else {
                field.style.borderColor = "#d9dee7";
            }
        });

        const email = contactForm.querySelector('input[name="email"]');
        if (email && !email.checkValidity()) {
            email.style.borderColor = "#d60000";
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            showContactFeedback("Please complete the required contact fields.");
        } else {
            showContactFeedback("Sending your inquiry...");
        }
    });
}

const contactSubmitStatus = <?php echo json_encode($status); ?>;
if (contactSubmitStatus === "success") showContactFeedback("Your inquiry was sent successfully.");
if (contactSubmitStatus === "error") showContactFeedback("Please complete all required fields properly.");

function openMap() {
    showContactFeedback("Opening map directions...");
    window.open("https://www.google.com/maps/search/?api=1&query=Makati+City+Metro+Manila+Philippines", "_blank");
}

function contactQuickAnswer(topic) {
    const service = document.querySelector('select[name="service"]');
    const turnaround = document.querySelector('select[name="turnaround"]');
    const message = document.querySelector('textarea[name="message"]');

    if (topic === "Turnaround Time" && turnaround) turnaround.value = "Rush Order";
    if ((topic === "Request a Quote" || topic === "File Guide") && service) service.value = "Custom Design";

    if (message) {
        message.value = `Hi Printify, I need help with ${topic}.`;
        message.focus();
        message.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    showContactFeedback(`${topic} selected.`);
}

function focusContactMessage() {
    const message = document.querySelector('textarea[name="message"]');
    if (message) {
        message.focus();
        message.scrollIntoView({ behavior: "smooth", block: "center" });
        showContactFeedback("Message box is ready.");
    }
}
</script>
