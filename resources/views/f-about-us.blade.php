<style>
/* ABOUT US SECTION ONLY */
#about.about-premium-page{display:block;background:#fff;color:#111;font-family:'Inter','Poppins',sans-serif;overflow:visible;scroll-margin-top:95px}
#about *{box-sizing:border-box}
#about .about-wrap{width:min(1500px,calc(100% - 30px));margin:0 auto;padding:22px 0 28px}
#about .about-top{display:grid;grid-template-columns:minmax(360px,500px) 1fr;gap:34px;align-items:start}
#about .section-label{margin:0 0 9px;color:#ff5a12;font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase}
#about .about-heading{margin:0;color:#111;font-family:'Playfair Display',serif;font-size:28px;line-height:1.12;font-weight:700;letter-spacing:0}
#about .orange-line{width:42px;height:3px;margin:12px 0 17px;border-radius:99px;background:#ff5a12}
#about .story-text{margin:0;color:#555;font-size:13px;line-height:1.68}
#about .mission-card,#about .story-card,#about .stat-cta-box,#about .about-process-strip{width:100%;padding:12px;border:1px solid #111827;border-radius:18px;background:#fff;box-shadow:0 16px 38px rgba(0,0,0,.05)}
#about .mission-card:hover,#about .story-card:hover,#about .stat-cta-box:hover,#about .about-process-strip:hover{background:#fff7f2;box-shadow:0 18px 38px rgba(255,90,18,.09)}
#about .mission-card{justify-self:start;max-width:800px}
#about .story-card{justify-self:stretch;align-self:start;max-width:none;min-height:auto;padding:10px 14px 11px;display:flex;flex-direction:column}
#about .story-card .orange-line{margin:8px 0 10px}
#about .story-card .story-text{font-size:12px;line-height:1.45}
#about .stat-cta-box{justify-self:start;max-width:800px;padding:12px 14px}

/* Mission / Vision / Process */
#about .mission-card>p:not(.section-label){margin:0;color:#555;font-size:13px;line-height:1.58}
#about .mv-tabs{width:min(100%,430px);margin:16px auto 14px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px;align-items:center;justify-content:center}
#about .mv-tab{width:100%;height:34px;border-radius:10px;border:1px solid #ff7a00;background:#ff7a00;color:#000;cursor:pointer;font-family:inherit;font-size:10px;font-weight:900;letter-spacing:0;text-align:center;display:flex;align-items:center;justify-content:center;transition:background-color .22s ease,border-color .22s ease,color .22s ease,box-shadow .22s ease}
#about .mv-tab.active,#about .mv-tab:hover{background:#111827;border-color:#111827;color:#fff;box-shadow:0 8px 18px rgba(17,24,39,.18)}
#about .mv-content{width:min(100%,470px);margin:0 auto;padding:14px 15px;border-radius:15px;background:#fff7f2;border:1px solid #ffe1d2;min-height:124px}
#about .mv-content h3{margin:0 0 7px;color:#111;font-size:17px;font-weight:900}
#about .mv-content p{margin:0 0 9px;color:#555;font-size:12.2px;line-height:1.58}
#about .mv-content p:last-child{margin-bottom:0}
#about .check-grid{margin-top:13px;display:grid;grid-template-columns:1fr 1fr;gap:8px 16px}
#about .check-item{color:#333;font-size:12px;font-weight:600}
#about .check-item i{color:#ff5a12;margin-right:8px}

/* Right top column */
#about .right-showcase{display:flex;flex-direction:column;gap:14px}
#about .values-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
#about .value-card{min-height:104px;padding:11px 12px;border:1px solid #111827;border-radius:13px;background:#fff;box-shadow:0 14px 32px rgba(0,0,0,.045);transition:.22s ease;cursor:pointer}
#about .value-card:hover,#about .value-card.is-active{transform:translateY(-3px);background:#fff7f2;border-color:#111827;box-shadow:0 18px 38px rgba(255,90,18,.09)}
#about .value-icon{width:32px;height:32px;margin-bottom:8px;border-radius:50%;display:grid;place-items:center;font-size:13px}
#about .orange{color:#ff5a12;background:rgba(255,90,18,.12)}
#about .blue{color:#1f7ae0;background:rgba(31,122,224,.12)}
#about .green{color:#209b55;background:rgba(32,155,85,.12)}
#about .purple{color:#8b55de;background:rgba(139,85,222,.12)}
#about .value-card h3{margin:0 0 5px;color:#111;font-size:12px;font-weight:900}
#about .value-card p{margin:0;color:#626262;font-size:10.5px;line-height:1.35}
#about .top-gallery{position:relative;display:grid;grid-template-columns:1.15fr .85fr;grid-template-rows:110px 110px;gap:8px}
#about .photo-box{overflow:hidden;border-radius:12px;background:#f7f7f7;border:1px solid #111827}
#about .photo-box.large{grid-row:1/3}
#about .photo-box img{width:100%;height:100%;object-fit:cover;display:block;transition:.35s ease}
#about .photo-box:hover img{transform:scale(1.05)}
#about .commit-badge{position:absolute;left:12px;bottom:12px;width:160px;padding:12px;border-radius:12px;color:#fff;background:linear-gradient(135deg,#ff5a12,#ff7b22);box-shadow:0 15px 32px rgba(255,90,18,.24)}
#about .commit-badge i{width:30px;height:30px;margin-bottom:6px;border-radius:50%;display:grid;place-items:center;background:#fff;color:#ff5a12}
#about .commit-badge strong{display:block;font-size:12.5px;font-weight:900}
#about .commit-badge small{display:block;margin-top:6px;font-size:10.5px;line-height:1.42}

/* Story + stats/cta area */
#about .bottom-area{margin-top:20px;display:grid;grid-template-columns:minmax(360px,500px) minmax(0,1fr);gap:34px;align-items:start;justify-content:stretch}
#about .story-column{display:flex;flex-direction:column;gap:30px;transform:translateY(-110px)}
#about .about-process-strip{padding:20px 20px;border-radius:13px;display:grid;grid-template-columns:repeat(4,1fr);gap:8px;position:relative}
#about .process-step{position:relative;min-height:56px;padding:8px 8px 8px 39px;border-radius:9px;background:#fff8f4}
#about .process-num{position:absolute;left:10px;top:-10px;width:28px;height:28px;border-radius:50%;display:grid;place-items:center;background:#ff7a00;color:#fff;font-size:10px;font-weight:900;box-shadow:0 8px 18px rgba(255,122,0,.2)}
#about .process-step i{position:absolute;left:11px;top:27px;color:#ff5a12;font-size:15px}
#about .process-step h3{margin:0 0 3px;font-family:Poppins,sans-serif;font-size:11px;font-weight:600;color:#111}
#about .process-step p{margin:0;color:#555;font-size:9.4px;line-height:1.25}
#about .about-outline-btn{margin-top:9px;height:32px;min-width:156px;padding:0 14px;border-radius:999px;border:1px solid #ff7a00;color:#000;background:#ff7a00;cursor:pointer;font-family:inherit;font-size:9.5px;font-weight:900;letter-spacing:0;white-space:nowrap;transition:background-color .22s ease,border-color .22s ease,color .22s ease,box-shadow .22s ease;align-self:flex-start}
#about .about-outline-btn:hover{background:#111827;border-color:#111827;color:#fff;box-shadow:0 8px 18px rgba(17,24,39,.18)}
#about .about-more{display:none;margin-top:9px;padding:10px 12px;border:1px solid #ffe0d2;border-radius:13px;background:#fff7f2;color:#444;font-size:11.5px;line-height:1.45}
#about .about-more.show{display:block}
#about .mini-stats{width:100%;padding:0;border:0;border-radius:0;background:transparent;box-shadow:none;display:grid;grid-template-columns:repeat(2,1fr);gap:8px}
#about .mini-stat{display:flex;align-items:center;gap:8px;padding:8px;border-radius:13px;background:#fff8f4}
#about .mini-icon{width:31px;height:31px;min-width:31px;border-radius:50%;display:grid;place-items:center;background:rgba(255,90,18,.13);color:#ff5a12;font-size:12px}
#about .stat-number{display:block;color:#111;font-size:17px;font-weight:900;line-height:1}
#about .stat-label{display:block;margin-top:3px;color:#666;font-size:9.2px;line-height:1.1}
#about .about-cta{margin-top:9px;padding:10px 0 0;border-radius:0;background:transparent;border:0;border-top:1px solid #f1e7df;display:grid;grid-template-columns:minmax(0,1fr) auto;align-items:center;gap:12px}
#about .about-cta>div{min-width:0}
#about .about-cta h3{margin:0;color:#111;font-size:15px;font-weight:900}
#about .about-cta p{margin:4px 0 0;color:#666;font-size:10.8px;line-height:1.35}
#about .about-cta-btn{height:34px;min-width:116px;padding:0 14px;border:1px solid #ff7a00;border-radius:999px;background:#ff7a00;color:#000;font-family:inherit;font-size:9.5px;font-weight:900;letter-spacing:0;cursor:pointer;white-space:nowrap;transition:background-color .22s ease,box-shadow .22s ease,color .22s ease,border-color .22s ease}
#about .about-cta-btn:hover{background:#111827;border-color:#111827;color:#fff;box-shadow:0 8px 18px rgba(17,24,39,.18)}

@media(min-width:1251px){
#about .bottom-area{transform:none;margin-bottom:0}
#about .story-card{transform:translateY(-14px)}
}

@media(max-width:1250px){
#about .about-wrap{width:min(100% - 32px,1000px)}
#about .about-top,#about .bottom-area{grid-template-columns:1fr;gap:26px;justify-content:stretch}
#about .bottom-area{margin-top:12px;transform:none;margin-bottom:0}
#about .values-grid{grid-template-columns:repeat(2,1fr)}
#about .story-card,#about .stat-cta-box,#about .mission-card{max-width:none}
}
@media(max-width:760px){
#about .about-wrap{width:calc(100% - 24px);padding-top:34px}
#about .values-grid,#about .mini-stats{grid-template-columns:1fr}
#about .about-process-strip{grid-template-columns:1fr}
#about .mv-tabs{width:100%;grid-template-columns:repeat(3,1fr);gap:8px}
#about .mv-tab{font-size:9px}
#about .top-gallery{grid-template-columns:1fr;grid-template-rows:auto}
#about .photo-box{height:180px}
#about .photo-box.large{grid-row:auto}
#about .commit-badge{position:static;width:100%;margin-top:12px}
#about .check-grid{grid-template-columns:1fr}
#about .about-cta{display:block}
#about .about-cta-btn{margin-top:13px;width:100%}
}
</style>

<section id="about" class="about-premium-page">
    <div class="about-wrap">
        <div class="about-top">
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
                        <p>We aim to make every transaction simple and stress-free through clear assistance, organized production, and consistent quality checking before release.</p>
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
            </div>

            <div class="right-showcase">
                <div class="values-grid">
                    <div class="value-card is-active" tabindex="0">
                        <div class="value-icon orange"><i class="fa-solid fa-star"></i></div>
                        <h3>Premium Quality</h3>
                        <p>Crisp details, vibrant colors, and durable finishes in every print.</p>
                    </div>
                    <div class="value-card" tabindex="0">
                        <div class="value-icon blue"><i class="fa-solid fa-truck-fast"></i></div>
                        <h3>Fast Turnaround</h3>
                        <p>Efficient processes and dedicated teams to meet your deadlines.</p>
                    </div>
                    <div class="value-card" tabindex="0">
                        <div class="value-icon green"><i class="fa-solid fa-tags"></i></div>
                        <h3>Affordable Pricing</h3>
                        <p>Competitive rates without compromising on quality.</p>
                    </div>
                    <div class="value-card" tabindex="0">
                        <div class="value-icon purple"><i class="fa-solid fa-users"></i></div>
                        <h3>Customer First</h3>
                        <p>We listen, care, and go the extra mile for our clients.</p>
                    </div>
                </div>

                <div class="top-gallery">
                    <div class="photo-box large"><img src="{{ asset('images/Homesld1.jpg') }}" alt="Printing machine"></div>
                    <div class="photo-box"><img src="{{ asset('images/Homesld2.jpg') }}" alt="Print workspace"></div>
                    <div class="photo-box"><img src="{{ asset('images/Homesld3.jpg') }}" alt="Printed materials"></div>
                    <div class="commit-badge">
                        <i class="fa-solid fa-award"></i>
                        <strong>Committed to Excellence</strong>
                        <small>Every project is handled with care, precision, and attention to detail.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-area">
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
                        <div class="mini-icon"><i class="fa-solid fa-print"></i></div>
                        <div>
                            <span class="stat-number about-counter" data-value="25000" data-suffix="+">25,000+</span>
                            <span class="stat-label">Orders Completed</span>
                        </div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-icon"><i class="fa-solid fa-user-group"></i></div>
                        <div>
                            <span class="stat-number about-counter" data-value="8000" data-suffix="+">8,000+</span>
                            <span class="stat-label">Happy Clients</span>
                        </div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-icon"><i class="fa-regular fa-clock"></i></div>
                        <div>
                            <span class="stat-number about-counter" data-value="98" data-suffix="%">98%</span>
                            <span class="stat-label">On-Time Delivery Rate</span>
                        </div>
                    </div>
                </div>

                <div class="about-cta">
                    <div>
                        <h3>Ready to print your next project?</h3>
                        <p>Choose a service, send your details, and our team will help you prepare your order properly.</p>
                    </div>
                    <button type="button" class="about-cta-btn" onclick="aboutGoToContact()">CONTACT US <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="story-column">
                <div class="about-process-strip">
                    <div class="process-step"><span class="process-num">01</span><i class="fa-solid fa-comments"></i><h3>Consultation</h3><p>We listen to your needs and provide the best solution.</p></div>
                    <div class="process-step"><span class="process-num">02</span><i class="fa-solid fa-print"></i><h3>Production</h3><p>We print with precision using top-quality materials.</p></div>
                    <div class="process-step"><span class="process-num">03</span><i class="fa-solid fa-cube"></i><h3>Quality Check</h3><p>Every item is inspected for perfect results.</p></div>
                    <div class="process-step"><span class="process-num">04</span><i class="fa-solid fa-truck"></i><h3>Delivery</h3><p>On-time delivery, ready to impress.</p></div>
                </div>
                <div class="story-card">
                    <p class="section-label">OUR STORY</p>
                    <h2 class="about-heading">Built on Passion. Driven by Quality.</h2>
                    <div class="orange-line"></div>
                    <p class="story-text">At Printify &amp; Co., we are a leading provider of high-quality printing solutions, dedicated to helping businesses and individuals bring their ideas to life with precision and care. Established with a passion for printing, we continue to grow through reliable service, modern production standards, and a strong commitment to customer satisfaction.</p>

                    <button type="button" class="about-outline-btn" id="aboutLearnBtn">LEARN MORE ABOUT US <i class="fa-solid fa-arrow-right"></i></button>

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
    let countersStarted=false;

    window.aboutGoToContact=function(){
        const contact=document.getElementById('contact');
        if(contact){contact.scrollIntoView({behavior:'smooth',block:'start'});}
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
                if(value>=target){value=target;clearInterval(timer);}
                counter.textContent=formatNumber(value,suffix);
            },22);
        });
    }

    if('IntersectionObserver' in window && about){
        const observer=new IntersectionObserver(entries=>{
            entries.forEach(entry=>{if(entry.isIntersecting)runCounters();});
        },{threshold:.22});
        observer.observe(about);
    }else{
        setTimeout(runCounters,500);
    }

    const learnBtn=document.getElementById('aboutLearnBtn');
    const moreText=document.getElementById('aboutMoreText');
    if(learnBtn && moreText){
        learnBtn.addEventListener('click',function(){
            moreText.classList.toggle('show');
            learnBtn.innerHTML=moreText.classList.contains('show')
                ? 'SHOW LESS <i class="fa-solid fa-arrow-up"></i>'
                : 'LEARN MORE ABOUT US <i class="fa-solid fa-arrow-right"></i>';
        });
    }

    const cards=document.querySelectorAll('#about .value-card');
    cards.forEach(card=>{
        const activate=()=>{
            cards.forEach(c=>c.classList.remove('is-active'));
            card.classList.add('is-active');
        };
        card.addEventListener('click',activate);
        card.addEventListener('keypress',e=>{if(e.key==='Enter')activate();});
    });

    const content={
        mission:{
            title:'Our Mission',
            text:[
                'To deliver high-quality, affordable, and reliable printing services that help customers turn ideas, designs, and business materials into professional printed outputs.',
                'We aim to make every transaction simple and stress-free through clear assistance, organized production, and consistent quality checking before release.'
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
            if(mvTitle)mvTitle.textContent=data.title;
            if(mvText)mvText.innerHTML=data.text.map(paragraph=>`<p>${paragraph}</p>`).join('');
        });
    });
});
</script>
