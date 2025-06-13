<?php
session_start();
include 'header.php';
$pdo = new PDO('mysql:host=localhost;dbname=eshop', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cyborg - Featured Motorbikes</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="assets/css/owl.css">
  <link rel="stylesheet" href="assets/css/animate.css">
</head>
<body>

<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
  <div class="preloader-inner">
    <span class="dot"></span>
    <div class="dots">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</div>
<!-- ***** Preloader End ***** -->

<div class="container mt-5">
  <div class="row">
    <div class="col-lg-12">
      <div class="page-content">

        <!-- ***** Featured Bikes Start ***** -->
        <div class="featured-games header-text">
          <div class="heading-section text-center mb-4">
            <h4><em>Featured</em> Motorbikes</h4>
          </div>

          <div class="owl-features owl-carousel">
            <?php
            $stmt = $pdo->query("SELECT * FROM motorbikes ORDER BY id DESC");
            $bikes = $stmt->fetchAll();

            foreach ($bikes as $bike): ?>
              <div class="item">
                <div class="thumb">
                  <img src="uploads/<?= htmlspecialchars($bike['image']); ?>" alt="" style="height:200px;object-fit:cover;">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                <h4><?= htmlspecialchars($bike['title']); ?><br><span><?= htmlspecialchars($bike['description']); ?></span></h4>
                <ul>
                  <li><i class="fa fa-star"></i> 4.8</li>
                  <li><i class="fa fa-download"></i> <?= htmlspecialchars($bike['additional_info']); ?></li>
                </ul>
                <?php if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
                  <div class="mt-2">
                    <a href="edit.php?id=<?= $bike['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="admin-panel.php?delete=<?= $bike['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this bike?')">Delete</a>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
            <div class="text-center mt-4">
              <a href="admin-panel.php" class="btn btn-success">+ Add New Bike</a>
            </div>
          <?php endif; ?>

        </div>
        <!-- ***** Featured Bikes End ***** -->

        <!-- Remaining HTML content unchanged below -->

        </div>
        <!-- ***** Featured Bikes End ***** -->

        <!-- ***** Riding Tips Start ***** -->
        <div class="start-stream mt-5">
          <div class="col-lg-12">
            <div class="heading-section">
              <h4><em>How To Start</em> Riding a Bike</h4>
            </div>
            <div class="row">
              <div class="col-lg-4">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/service-01.jpg" alt="" style="max-width: 60px; border-radius: 50%;">
                  </div>
                  <h4>Get the Right Gear</h4>
                  <p>Wear a helmet and protective clothing. Make sure your bike is the right size and in good working condition before you start.</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/service-02.jpg" alt="" style="max-width: 60px; border-radius: 50%;">
                  </div>
                  <h4>Learn the Basics</h4>
                  <p>Practice balancing, starting, and stopping in a safe, open area. Get comfortable with the controls and how your bike responds.</p>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="item">
                  <div class="icon">
                    <img src="assets/images/service-03.jpg" alt="" style="max-width: 60px; border-radius: 50%;">
                  </div>
                  <h4>Start Riding</h4>
                  <p>Begin with short rides at low speed. Gradually build your confidence and skills before riding in traffic or on busy roads.</p>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="main-button">
                  <a href="profile.php">Learn More Tips</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ***** Riding Tips End ***** -->

        <!-- ***** Events Section Start ***** -->
        <div class="live-stream mt-5">
          <div class="col-lg-12">
            <div class="heading-section">
              <h4><em>Most Popular</em> Events</h4>
            </div>
          </div>
          <div class="row">
            <?php
            $events = [
              ["image" => "event1.jpg", "title" => "International Bike Expo 2024", "organizer" => "KenganC"],
              ["image" => "event2.jpg", "title" => "Annual City Bike Rally", "organizer" => "LunaMa"],
              ["image" => "event3.jpg", "title" => "Classic Motorbike Parade", "organizer" => "Areluwa"],
              ["image" => "event4.jpg", "title" => "Mountain Adventure Ride", "organizer" => "GangTm"]
            ];

            foreach ($events as $event): ?>
              <div class="col-lg-3 col-sm-6">
                <div class="item">
                  <div class="thumb">
                    <img src="assets/images/<?= $event['image']; ?>" alt="">
                    <div class="hover-effect">
                      <div class="content">
                        <div class="live">
                          <a href="#">Live</a>
                        </div>
                        <ul>
                          <li><a href="#"><i class="fa fa-eye"></i> 1.2K</a></li>
                          <li><a href="#"><i class="fa fa-flag-checkered"></i> MotoGP</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="down-content">
                    <div class="avatar">
                      <img src="assets/images/<?= $event['image']; ?>" alt="" style="max-width: 46px; border-radius: 50%; float: left;">
                    </div>
                    <span><i class="fa fa-check"></i> <?= $event['organizer']; ?></span>
                    <h4><?= $event['title']; ?></h4>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            <div class="col-lg-12">
              <div class="main-button">
                <a href="streams.html">Discover All Events</a>
              </div>
            </div>
          </div>
        </div>
        <!-- ***** Events Section End ***** -->

      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/isotope.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/tabs.js"></script>
<script src="assets/js/popup.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
