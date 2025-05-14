<?= $this->extend('/shared/main'); ?>

<?php 
        $sessionUserID = session()->get('id_user');
        $sessionUserRole = session()->get('user_role');

?>

<?= $this->section('css') ?>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Explora&display=swap" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Explora&family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

        <style>
                .explora-regular {
                        font-family: "Explora", serif;
                        font-weight: 400;
                        font-style: normal;
                }

                .pt-serif-regular {
                        font-family: "PT Serif", serif;
                        font-weight: 400;
                        font-style: normal;
                }
        </style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

        <nav class="navbar bg-light shadow-sm fixed-top">
                <div class="container-fluid">
                        <img src="/resources/images/logo.png" height="45">
                        <?php if ($sessionUserRole == 1): ?>
                                <form action="/searchquery" method="POST" class="d-flex input-group w-auto">
                                        <input
                                                type="text"
                                                name="searchquery"
                                                class="form-control rounded"
                                                placeholder="Search"
                                        />
                                        <button type="submit" class="btn btn-link px-3">
                                                <i class="fas fa-search fa-lg text-dark"></i>
                                        </button>
                                </form>
                        <?php endif; ?>
                        <?php if ($sessionUserRole == 2): ?>
                                <div>
                                        <span class="me-2"> <?= $UserData['firstname'] . ' ' . $UserData['lastname']; ?></span>
                                        <?php 
                                                $pngPath = "/resources/images/profilepics/$sessionUserID.png";
                                                $jpgPath = "/resources/images/profilepics/$sessionUserID.jpg";

                                                $profilepic = file_exists(FCPATH . ltrim($pngPath, '/')) 
                                                ? $pngPath 
                                                : (file_exists(FCPATH . ltrim($jpgPath, '/')) 
                                                ? $jpgPath 
                                                : "/resources/images/default.png");
                                        ?>
                                        <img src="<?=  $profilepic; ?>" class="rounded-circle profilepic" height="40" width="40" loading="lazy"/>         
                                </div>
                        <?php endif; ?>
                </div>
        </nav>
 
        <?php if ($sessionUserRole == 1): ?>
        <div class="bg-image position-relative">
                <video src="resources/images/home.mp4" class="w-100" autoplay muted loop style="display: block;"></video>
                <div class="mask position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);">
                        <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="text-center text-white">
                                        <div class="mb-3">
                                                <img src="resources/images/logo-full.png" class="rounded mb-2" height="80">
                                                <div class="h3 pt-serif-regular">Home Vest</div>
                                        </div>
                                        <div class="mb-4 px-3">
                                                <span class="explora-regular h1">Your 224: Plan 2day, Invest 2tomorrow, Live in comfot 4 life</span>
                                        </div>
                                        <div>
                                                <a href="/homeslist" class="btn btn-outline-light mb-3 w-50">Homes</a>
                                        </div>
                                        <div>
                                                <a href="/agentslist" class="btn btn-outline-light mb-3 w-50">Agents</a>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
        
        <div class="container my-3">
                <div class="text-center mb-3 fw-bold">About Us</div>
                <div class="mb-3">
                        At Home Vest, we believe finding your dream home should be as simple as having a conversation. 
                        That’s why we created a smart, seamless real estate app that connects you directly with trusted agents 
                        — anytime, anywhere. Whether you’re buying or just exploring, 
                        Home Vest lets you chat instantly with local agents to get answers, schedule viewings, and make informed decisions, 
                        all from the palm of your hand. No endless forms, no waiting games — just real conversations that move you closer to home.
                </div>    
        </div>
        <?php endif; ?>

        <?php if ($sessionUserRole == 2): ?>
        <style>
                body {
                        background-color: rgb(225,225,229) !important;
                }
        </style>

        <div class="container-fluid">
                <div style="padding-top: 80px;">
                <?php
                        if (empty($HomeDevelopers)): ?>
                        <p class="text-center">You have no affiliation/s, please contact the administrator.</p>
                <?php
                        else:
                        usort($HomeDevelopers, function($a, $b) {
                                return strcmp($a['developer_name'], $b['developer_name']);
                        });
                        foreach ($HomeDevelopers as $hd): ?>
                                <div class="card text-center mb-3">
                                <div class="card-body">
                                        <h5 class="card-title mb-3"><?= $hd['developer_name']; ?></h5>
                                        <div>
                                                <a href="/homeslisting?developer=<?= $hd['id_developer'] ?>&devname=<?= $hd['developer_name'] ?>" type="submit" class="btn btn-primary">Add Listing</a>
                                        </div>
                                </div>
                                </div>
                        <?php endforeach;
                        endif;
                ?>
                </div>
        </div>
        <?php endif; ?>


        <div style="height: 50px;"></div>
        
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Start of ChatBot (www.chatbot.com) code -->
<script>
  window.__ow = window.__ow || {};
  window.__ow.organizationId = "e9e38bcd-79de-4ac2-81b6-b1f438985e80";
  window.__ow.template_id = "0bd08b29-46a1-4d86-a745-95bb269664b8";
  window.__ow.integration_name = "manual_settings";
  window.__ow.product_name = "chatbot";   
  ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[OpenWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.openwidget.com/openwidget.js",t.head.appendChild(n)}};!n.__ow.asyncInit&&e.init(),n.OpenWidget=n.OpenWidget||e}(window,document,[].slice))
</script>
<noscript>You need to <a href="https://www.chatbot.com/help/chat-widget/enable-javascript-in-your-browser/" rel="noopener nofollow">enable JavaScript</a> in order to use the AI chatbot tool powered by <a href="https://www.chatbot.com/" rel="noopener nofollow" target="_blank">ChatBot</a></noscript>
<!-- End of ChatBot code -->
<?= $this->endSection() ?>






