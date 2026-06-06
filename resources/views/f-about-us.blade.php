<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Playfair+Display:wght@700&family=Poppins:wght@600&display=swap');
#about.about-premium-page{
    display:block;
    background:#fff;
    color:#111;
    font-family:'Inter',system-ui,sans-serif;
    font-weight:400;
    letter-spacing:.1px;
    overflow:visible;
    scroll-margin-top:95px;
}
#about *{
    box-sizing:border-box;
}
#about .about-wrap{
    width:min(1270px,calc(100% - 118px));
    max-width:1270px;
    margin:0 18px 0 100px;
    padding:40px 0 70px;
}
#about .about-layout{
    display:grid;
    grid-template-columns:minmax(360px,500px) minmax(0,1fr);
    gap:25px;
    align-items:start;
}
#about .section-label{
    margin:0 0 9px;
    color:#ff5a12;
    font-size:11px;
    font-weight:600;
    letter-spacing:2px;
    text-transform:uppercase;
}
#about .about-heading{
    margin:0;
    color:#111;
    font-family:'Playfair Display',Georgia,serif;
    font-size:28px;
    line-height:1.12;
    font-weight:700;
    letter-spacing:0;
}
#about .orange-line{
    width:42px;
    height:3px;
    margin:12px 0 17px;
    border-radius:99px;
    background:#ff5a12;
}
#about .story-text{
    margin:0;
    color:#555;
    font-size:12px;
    line-height:1.38;
}
#about .mission-card{
    width:100%;
    padding:12px;
    min-height:650px;
    border:1px solid #111;
    border-radius:12px;
    background:#fff;
    box-shadow:none!important;
}
#about .mission-card:hover{
    background:#fff;
    box-shadow:none!important;
}
#about .mission-card>p:not(.section-label){
    margin:0;
    color:#555;
    font-size:12px;
    line-height:1.38;
}
#about .mv-tabs{
    width:min(100%,430px);
    margin:16px auto 14px;
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
    align-items:center;
    justify-content:center;
}
#about .mv-tab{
    width:100%;
    height:34px;
    border:0!important;
    border-radius:10px;
    background:#ff7a00;
    color:#111827;
    cursor:pointer;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:11px;
    font-weight:600;
    letter-spacing:0;
    text-align:center;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:none!important;
    transition:background-color .22s ease,color .22s ease;
}
#about .mv-tab:nth-child(2),
#about .mv-tab:nth-child(3){
    background:#ff7a00;
    color:#111827;
}
#about .mv-tab.active,
#about .mv-tab:hover{
    background:#111827!important;
    color:#fff!important;
}
#about .mv-content{
    width:min(100%,470px);
    margin:0 auto;
    padding:14px 15px;
    border-radius:15px;
    background:#fff7f2;
    border:1px solid #ffe1d2;
    min-height:124px;
}
#about .mv-content h3{
    margin:0 0 7px;
    color:#111;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:17px;
    font-weight:600;
}
#about .mv-content p{
    margin:0 0 9px;
    color:#555;
    font-size:12px;
    line-height:1.38;
}
#about .mv-content p:last-child{
    margin-bottom:0;
}
#about .check-grid{
    margin-top:13px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:8px 16px;
}
#about .check-item{
    color:#333;
    font-size:12px;
    font-weight:600;
}
#about .check-item i{
    color:#ff5a12;
    margin-right:8px;
}
#about .stat-cta-box{
    width:100%;
    margin-top:14px;
    padding:0;
    border:0;
    background:transparent;
    box-shadow:none!important;
}
#about .mini-stats{
    width:100%;
    padding:0;
    border:0;
    border-radius:0;
    background:transparent;
    box-shadow:none;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:8px;
}
#about .mini-stat{
    display:flex;
    align-items:center;
    gap:8px;
    min-height:46px;
    padding:7px 8px;
    border-radius:10px;
    background:#fff8f4;
}
#about .mini-icon{
    width:28px;
    height:28px;
    min-width:28px;
    border-radius:50%;
    display:grid;
    place-items:center;
    background:transparent;
    color:#ff7a00;
    font-size:12px;
}
#about .stat-number{
    display:block;
    color:#111;
    font-size:15px;
    font-weight:700;
    line-height:1;
}
#about .stat-label{
    display:block;
    margin-top:3px;
    color:#666;
    font-size:9px;
    line-height:1.1;
}
#about .about-cta{
    margin-top:8px;
    padding:8px 0 0;
    border-top:1px solid #f1e7df;
    display:grid;
    grid-template-columns:minmax(0,1fr) auto;
    align-items:center;
    gap:10px;
}
#about .about-cta h3{
    margin:0;
    color:#111;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:13px;
    font-weight:600;
}
#about .about-cta p{
    margin:4px 0 0;
    color:#666;
    font-size:10px;
    line-height:1.3;
}
#about .about-cta-btn{
    width:156px;
    height:34px;
    padding:0 14px;
    border:0!important;
    border-radius:10px;
    background:#ff7a00;
    color:#111827;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:9.5px;
    font-weight:900;
    cursor:pointer;
    white-space:nowrap;
    box-shadow:none!important;
    transition:background-color .22s ease,color .22s ease;
}
#about .about-cta-btn:hover{
    background:#111827!important;
    color:#fff!important;
}
#about .right-showcase{
    display:flex;
    flex-direction:column;
    gap:12px;
}
#about .values-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
}
#about .value-card{
    min-height:104px;
    padding:11px 12px;
    border-radius:10px;
    background:#fff;
    box-shadow:none!important;
    cursor:pointer;
    transition:.22s ease;
}
#about .value-card:nth-child(1){
    border:1px solid #ff5b5b;
}
#about .value-card:nth-child(2){
    border:1px solid #18b8bd;
}
#about .value-card:nth-child(3){
    border:1px solid #f2bd2f;
}
#about .value-card:nth-child(4){
    border:1px solid #f05aa2;
}
#about .value-card:hover,
#about .value-card.is-active{
    transform:none!important;
    background:#fff!important;
    box-shadow:none!important;
}
#about .value-icon{
    width:28px;
    height:28px;
    margin-bottom:8px;
    border-radius:50%;
    display:grid;
    place-items:center;
    font-size:17px;
    background:transparent!important;
    box-shadow:none!important;
}
#about .orange{
    color:#ff1744!important;
    background:transparent!important;
}
#about .blue{
    color:#009da5!important;
    background:transparent!important;
}
#about .green{
    color:#e8a600!important;
    background:transparent!important;
}
#about .purple{
    color:#e91e63!important;
    background:transparent!important;
}
#about .value-card h3{
    margin:0 0 5px;
    color:#111;
    font-size:12px;
    font-family:'Poppins',system-ui,sans-serif;
    font-weight:600;
}
#about .value-card p{
    margin:0;
    color:#626262;
    font-size:10.5px;
    line-height:1.35;
}
#about .top-gallery{
    position:relative;
    display:grid;
    grid-template-columns:1.15fr .85fr;
    grid-template-rows:110px 110px;
    gap:8px;
}
#about .photo-box{
    overflow:hidden;
    border-radius:9px;
    background:#f7f7f7;
    border:0;
    box-shadow:none!important;
}
#about .photo-box.large{
    grid-row:1/3;
}
#about .photo-box img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
    transition:.35s ease;
}
#about .photo-box:hover img{
    transform:scale(1.05);
}
#about .commit-badge{
    position:absolute;
    left:12px;
    bottom:12px;
    width:160px;
    padding:12px;
    border-radius:10px;
    color:#fff;
    background:#ff7a00;
    box-shadow:none!important;
}
#about .commit-badge i{
    width:30px;
    height:30px;
    margin-bottom:6px;
    border-radius:50%;
    display:grid;
    place-items:center;
    background:transparent!important;
    color:#111827;
    box-shadow:none!important;
}
#about .commit-badge strong{
    display:block;
    font-size:12.5px;
    font-family:'Poppins',system-ui,sans-serif;
    font-weight:600;
}
#about .commit-badge small{
    display:block;
    margin-top:6px;
    font-size:10.5px;
    line-height:1.42;
}
#about .about-process-strip{
    width:100%;
    padding:0;
    border:0;
    border-radius:0;
    background:transparent;
    box-shadow:none!important;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:10px;
    position:relative;
    margin-top:2px;
}
#about .process-step{
    position:relative;
    min-height:78px;
    padding:15px 10px 11px 48px;
    border-radius:10px;
    background:#fff;
    box-shadow:none!important;
}
#about .process-step:nth-child(1){
    border:1px solid #2997ff;
}
#about .process-step:nth-child(2){
    border:1px solid #33b94f;
}
#about .process-step:nth-child(3){
    border:1px solid #ff8c28;
}
#about .process-step:nth-child(4){
    border:1px solid #7c4dff;
}
#about .process-step:hover{
    background:#fff;
}
#about .process-num{
    position:absolute;
    left:18px;
    top:13px;
    width:auto;
    height:auto;
    background:transparent!important;
    color:#111827;
    font-size:10px;
    font-weight:700;
    box-shadow:none!important;
}
#about .process-step i{
    position:absolute;
    left:16px;
    top:35px;
    font-size:20px;
}
#about .process-step:nth-child(1) .process-num,
#about .process-step:nth-child(1) i{
    color:#2997ff!important;
}
#about .process-step:nth-child(2) .process-num,
#about .process-step:nth-child(2) i{
    color:#33b94f!important;
}
#about .process-step:nth-child(3) .process-num,
#about .process-step:nth-child(3) i{
    color:#ff8c28!important;
}
#about .process-step:nth-child(4) .process-num,
#about .process-step:nth-child(4) i{
    color:#7c4dff!important;
}
#about .process-step h3{
    margin:0 0 4px;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:12px;
    font-weight:600;
    color:#111;
}
#about .process-step p{
    margin:0;
    color:#555;
    font-size:9.4px;
    line-height:1.25;
}
#about .story-card{
    width:100%;
    padding:10px 4px 15px;
    border:0;
    border-radius:0;
    background:transparent;
    box-shadow:none!important;
    display:grid;
    grid-template-columns:1fr auto;
    column-gap:18px;
    align-items:end;
    margin-top:0;
}
#about .story-card:hover{
    background:#fff;
    box-shadow:none!important;
}
#about .story-card .section-label,
#about .story-card .about-heading,
#about .story-card .orange-line,
#about .story-card .story-text,
#about .story-card .about-more{
    grid-column:1 / 2;
}
#about .story-card .orange-line{
    margin:8px 0 10px;
}
#about .about-outline-btn{
    grid-column:2 / 3;
    grid-row:4 / 5;
    justify-self:end;
    align-self:end;
    width:156px;
    height:34px;
    padding:0 14px;
    border:0!important;
    border-radius:10px;
    color:#111827;
    background:#ff7a00;
    cursor:pointer;
    font-family:'Poppins',system-ui,sans-serif;
    font-size:9.5px;
    font-weight:900;
    white-space:nowrap;
    box-shadow:none!important;
    transition:background-color .22s ease,color .22s ease;
}
#about .about-outline-btn:hover{
    background:#111827!important;
    color:#fff!important;
}
#about .about-more{
    display:none;
    margin-top:9px;
    padding:10px 12px;
    border:1px solid #ffe0d2;
    border-radius:13px;
    background:#fff7f2;
    color:#444;
    font-size:11.5px;
    line-height:1.45;
}
#about .about-more.show{
    display:block;
}
#about .about-feedback{
    position:fixed;
    top:18px;
    left:50%;
    z-index:9999;
    transform:translate(-50%,-18px);
    min-width:260px;
    max-width:calc(100% - 32px);
    padding:10px 16px;
    border-radius:999px;
    background:#111827;
    color:#fff;
    font-family:'Inter',system-ui,sans-serif;
    font-size:12px;
    font-weight:400;
    letter-spacing:.1px;
    text-align:center;
    opacity:0;
    pointer-events:none;
    box-shadow:0 10px 28px rgba(0,0,0,.18);
    transition:opacity .22s ease,transform .22s ease;
}
#about .about-feedback.show{
    opacity:1;
    transform:translate(-50%,0);
}
@media(max-width:1250px){
    #about .about-wrap{
        width:min(100% - 32px,1000px);
        margin:0 auto;
    }
    #about .about-layout{
        grid-template-columns:1fr;
        gap:22px;
    }
    #about .values-grid{
        grid-template-columns:repeat(2,1fr);
    }
}
@media(max-width:760px){
    #about .about-wrap{
        width:calc(100% - 24px);
        padding-top:26px;
    }
    #about .values-grid,
    #about .mini-stats{
        grid-template-columns:1fr;
    }
    #about .about-process-strip{
        grid-template-columns:1fr;
        gap:10px;
    }
    #about .mv-tabs{
        width:100%;
        grid-template-columns:repeat(3,1fr);
        gap:8px;
    }
    #about .mv-tab{
        font-size:9px;
    }
    #about .top-gallery{
        grid-template-columns:1fr;
        grid-template-rows:auto;
    }
    #about .photo-box{
        height:180px;
    }
    #about .photo-box.large{
        grid-row:auto;
    }
    #about .commit-badge{
        position:static;
        width:100%;
        margin-top:12px;
    }
    #about .check-grid{
        grid-template-columns:1fr;
    }
    #about .about-cta{
        display:block;
    }
    #about .about-cta-btn{
        margin-top:13px;
        width:100%;
    }
    #about .story-card{
        display:block;
    }
    #about .about-outline-btn{
        margin-top:13px;
        width:100%;
    }
}

</style>
<section id="about" class="about-premium-page">
    <div class="about-feedback" id="aboutFeedback" role="status" aria-live="polite"></div>
    <div class="about-wrap">
        <div class="about-layout">
            <div class="mission-card">
                <p class="section-label">ABOUT US</p>
                <h2 class="about-heading">Mission, Vision &amp; Service Direction</h2>
                <div class="orange-line"></div>
                <p>We keep our printing service clear, dependable, and customer-friendly from first inquiry up to final release.</p>
                <div class="mv-tabs">
                    <button type="button" class="mv-tab active" data-type="mission">MISSION</button>
                    <button type="button" class="mv-tab" data-type="vision">VISION</button>
                    <button type="button" class="mv-tab" data-type="process">PROCESS</button>
                </div>
                <div class="mv-content">
                    <h3 id="mvTitle">Our Mission</h3>
                    <div id="mvText">
                        <p>To deliver high-quality, affordable, and reliable printing services that help customers turn ideas, designs, and business materials into professional printed outputs.</p>
                        <p>We aim to make every transaction simple and stress-free through clear assistance, organized production, and consistent quality checked before release.</p>
                    </div>
                </div>
                <div class="check-grid">
                    <div class="check-item"><i class="fa-solid fa-check"></i>Modern printing equipment</div>
                    <div class="check-item"><i class="fa-solid fa-check"></i>Clear customer assistance</div>
                    <div class="check-item"><i class="fa-solid fa-check"></i>Quality checking before release</div>
                    <div class="check-item"><i class="fa-solid fa-check"></i>Fast and organized turnaround</div>
                    <div class="check-item"><i class="fa-solid fa-check"></i>Fair and transparent pricing</div>
                    <div class="check-item"><i class="fa-solid fa-check"></i>Printing solutions for every need</div>
                </div>
                <div class="stat-cta-box">
                    <div class="mini-stats">
                        <div class="mini-stat">
                            <div class="mini-icon"><i class="fa-regular fa-calendar-check"></i></div>
                            <div>
                                <span class="stat-number about-counter" data-value="10" data-suffix="+">10+</span>
                                <span class="stat-label">Years of Experience</span>
                            </div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-icon"><i class="fa-solid fa-star"></i></div>
                            <div>
                                <span class="stat-number about-counter" data-value="25000" data-suffix="+">25,000+</span>
                                <span class="stat-label">Clients Completed</span>
                            </div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-icon"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <span class="stat-number about-counter" data-value="5000" data-suffix="+">5,000+</span>
                                <span class="stat-label">Happy Clients</span>
                            </div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-icon"><i class="fa-solid fa-award"></i></div>
                            <div>
                                <span class="stat-number about-counter" data-value="98" data-suffix="%">98%</span>
                                <span class="stat-label">On-Time Delivery Rate</span>
                            </div>
                        </div>
                    </div>
                    <div class="about-cta">
                        <div>
                            <h3>Ready to print your next project?</h3>
                            <p>Choose a solution, see your details, and our team will help you prepare your order smoothly.</p>
                        </div>
                        <button type="button" class="about-cta-btn" onclick="aboutGoToContact()">
                            CONTACT US <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="right-showcase">
                <div class="values-grid">
                    <div class="value-card is-active" tabindex="0">
                        <div class="value-icon orange"><i class="fa-solid fa-star"></i></div>
                        <h3>Premium Quality</h3>
                        <p>Crisp details, vibrant colors, and durable materials in every print.</p>
                    </div>
                    <div class="value-card" tabindex="0">
                        <div class="value-icon blue"><i class="fa-solid fa-gauge-high"></i></div>
                        <h3>Fast Turnaround</h3>
                        <p>On-time or even ahead schedules with a focused process built to meet your deadlines.</p>
                    </div>
                    <div class="value-card" tabindex="0">
                        <div class="value-icon green"><i class="fa-solid fa-thumbs-up"></i></div>
                        <h3>Affordable Pricing</h3>
                        <p>Competitive rates without compromising on quality, crafted to add value.</p>
                    </div>
                    <div class="value-card" tabindex="0">
                        <div class="value-icon purple"><i class="fa-solid fa-users"></i></div>
                        <h3>Customer First</h3>
                        <p>We listen, care, and go the extra mile for our clients.</p>
                    </div>
                </div>
                <div class="top-gallery">
                    <div class="photo-box large">
                        <img src="{{ asset('images/Homesld1.jpg') }}" alt="Printing machine" loading="eager" decoding="sync" fetchpriority="high">
                    </div>
                    <div class="photo-box">
                        <img src="{{ asset('images/Homesld2.jpg') }}" alt="Print workspace" loading="eager" decoding="sync" fetchpriority="high">
                    </div>
                    <div class="photo-box">
                        <img src="{{ asset('images/Homesld3.jpg') }}" alt="Printed materials" loading="eager" decoding="sync" fetchpriority="high">
                    </div>
                    <div class="commit-badge">
                        <i class="fa-solid fa-award"></i>
                        <strong>Committed to Excellence</strong>
                        <small>Every project is handled with care, precision, and attention to detail.</small>
                    </div>
                </div>
                <div class="about-process-strip">
                    <div class="process-step">
                        <span class="process-num">01</span>
                        <i class="fa-solid fa-comments"></i>
                        <h3>Consultation</h3>
                        <p>We listen to your needs and provide the best solution.</p>
                    </div>
                    <div class="process-step">
                        <span class="process-num">02</span>
                        <i class="fa-solid fa-gear"></i>
                        <h3>Production</h3>
                        <p>We print with precision using top-quality materials.</p>
                    </div>
                    <div class="process-step">
                        <span class="process-num">03</span>
                        <i class="fa-solid fa-shield-halved"></i>
                        <h3>Quality Check</h3>
                        <p>Every item is inspected for perfect results.</p>
                    </div>
                    <div class="process-step">
                        <span class="process-num">04</span>
                        <i class="fa-solid fa-truck"></i>
                        <h3>Delivery</h3>
                        <p>On-time delivery, ready to impress.</p>
                    </div>
                </div>
                <div class="story-card">
                    <p class="section-label">OUR STORY</p>
                    <h2 class="about-heading">Built on Passion. Driven by Quality.</h2>
                    <div class="orange-line"></div>
                    <p class="story-text">
                        At Printify &amp; Co., we are a leading provider of high-quality printing solutions, dedicated to helping businesses and individuals bring their ideas to life with precision and care. Established with a passion for printing, we continue to grow through reliable service, modern production standards, and a strong commitment to customer satisfaction.
                    </p>
                    <button type="button" class="about-outline-btn" id="aboutLearnBtn">
                        LEARN MORE ABOUT US <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <div class="about-more" id="aboutMoreText">
                        We focus on quality printing, clear communication, and fast customer support from inquiry to final output. Our process helps customers understand available services, turnaround time, and production requirements before placing an order.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded',function(){
    const about=document.getElementById('about');
    const counters=document.querySelectorAll('#about .about-counter');
    const feedback=document.getElementById('aboutFeedback');
    let countersStarted=false;
    let feedbackTimer=null;
    function showAboutFeedback(message){
        if(!feedback)return;
        feedback.textContent=message;
        feedback.classList.add('show');
        clearTimeout(feedbackTimer);
        feedbackTimer=setTimeout(()=>{
            feedback.classList.remove('show');
        },1800);
    }
    window.aboutGoToContact=function(){
        const contact=document.getElementById('contact');
        if(contact){
            contact.scrollIntoView({
                behavior:'smooth',
                block:'start'
            });
            showAboutFeedback('Contact section opened.');
        }else{
            showAboutFeedback('Contact section is not available on this page yet.');
        }
    };
    function formatNumber(num,suffix){
        return num.toLocaleString('en-US') + suffix;
    }
    function runCounters(){
        if(countersStarted)return;
        countersStarted=true;
        counters.forEach(counter=>{
            const target=parseInt(counter.dataset.value,10);
            const suffix=counter.dataset.suffix || '';
            const step=Math.max(1,Math.ceil(target/42));
            let value=0;
            counter.textContent=formatNumber(0,suffix);
            const timer=setInterval(()=>{
                value+=step;
                if(value>=target){
                    value=target;
                    clearInterval(timer);
                }
                counter.textContent=formatNumber(value,suffix);
            },22);
        });
    }
    if('IntersectionObserver' in window && about){
        const observer=new IntersectionObserver(entries=>{
            entries.forEach(entry=>{
                if(entry.isIntersecting)runCounters();
            });
        },{
            threshold:.22
        });
        observer.observe(about);
    }else{
        setTimeout(runCounters,500);
    }
    const learnBtn=document.getElementById('aboutLearnBtn');
    const moreText=document.getElementById('aboutMoreText');
    if(learnBtn && moreText){
        learnBtn.addEventListener('click',function(){
            moreText.classList.toggle('show');
            const isOpen=moreText.classList.contains('show');
            learnBtn.innerHTML=isOpen
                ? 'SHOW LESS <i class="fa-solid fa-arrow-up"></i>'
                : 'LEARN MORE ABOUT US <i class="fa-solid fa-arrow-right"></i>';
            showAboutFeedback(isOpen ? 'More about us opened.' : 'About details closed.');
        });
    }
    const cards=document.querySelectorAll('#about .value-card');
    cards.forEach(card=>{
        const activate=()=>{
            cards.forEach(c=>c.classList.remove('is-active'));
            card.classList.add('is-active');
            const title=card.querySelector('h3');
            if(title)showAboutFeedback(title.textContent + ' selected.');
        };
        card.addEventListener('click',activate);
        card.addEventListener('keypress',e=>{
            if(e.key==='Enter')activate();
        });
    });
    const content={
        mission:{
            title:'Our Mission',
            text:[
                'To deliver high-quality, affordable, and reliable printing services that help customers turn ideas, designs, and business materials into professional printed outputs.',
                'We aim to make every transaction simple and stress-free through clear assistance, organized production, and consistent quality checked before release.'
            ]
        },
        vision:{
            title:'Our Vision',
            text:[
                'To become a trusted local printing partner known for consistent quality, fast turnaround, modern production, and excellent customer experience.',
                'We envision Printify & Co. as a reliable creative service provider that supports students, professionals, businesses, and organizations with accessible printing solutions.'
            ]
        },
        process:{
            title:'Our Process',
            text:[
                'We guide customers through service selection, file checking, order confirmation, production scheduling, quality inspection, and safe order release or delivery.'
            ]
        }
    };
    const tabs=document.querySelectorAll('#about .mv-tab');
    const mvTitle=document.getElementById('mvTitle');
    const mvText=document.getElementById('mvText');
    tabs.forEach(tab=>{
        tab.addEventListener('click',function(){
            const data=content[tab.dataset.type];
            tabs.forEach(t=>t.classList.remove('active'));
            tab.classList.add('active');
            if(mvTitle){
                mvTitle.textContent=data.title;
            }
            if(mvText){
                mvText.innerHTML=data.text.map(paragraph=>`<p>${paragraph}</p>`).join('');
            }
            showAboutFeedback(data.title + ' loaded.');
        });
    });
});
</script>
