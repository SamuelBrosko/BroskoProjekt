<?php
// qna.php
$pdo = new PDO('mysql:host=localhost;dbname=eshop', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query("SELECT * FROM qna ORDER BY created_at DESC LIMIT 10");
$qnaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>QnA - Motorbike Eshop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <link rel="stylesheet" href="assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="assets/css/owl.css">
  <link rel="stylesheet" href="assets/css/animate.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-5">
  <div class="page-content">
    <div class="heading-section text-center mb-4">
      <h4><em>Frequently</em> Asked Questions</h4>
    </div>

    <div class="accordion" id="qnaAccordion">
      <?php foreach ($qnaList as $index => $qna): ?>
        <div class="accordion-item mb-3">
          <h2 class="accordion-header" id="heading<?= $index ?>">
            <button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?>" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>"
                    aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>"
                    aria-controls="collapse<?= $index ?>">
              <?= htmlspecialchars($qna['question']) ?>
            </button>
          </h2>
          <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>"
               aria-labelledby="heading<?= $index ?>" data-bs-parent="#qnaAccordion">
            <div class="accordion-body">
              <?= nl2br(htmlspecialchars($qna['answer'])) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
