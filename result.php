<?php
  require_once("./class.php");
  if(strtoupper($_SERVER['REQUEST_METHOD']) != "POST"){
    header('Location: https://pumpkin.name/phy211/');
    exit();
  }
  $homeworks = array(
    new HomeworkGrade(1,$_POST['hw1'],28),
    new HomeworkGrade(2,$_POST['hw2'],30),
    new HomeworkGrade(3,$_POST['hw3'],28),
    new HomeworkGrade(4,$_POST['hw4'],46),
    new HomeworkGrade(5,$_POST['hw5'],30),
    new HomeworkGrade(6,$_POST['hw6'],26),
    new HomeworkGrade(7,$_POST['hw7'],28),
    new HomeworkGrade(8,$_POST['hw8'],30),
  );

  usort($homeworks,"HomeworkGrade::compare_sort_by_score");
  $homeworks[0]->drop();
  $homeworks[1]->drop();
  usort($homeworks,"HomeworkGrade::compare_sort_by_index");
  $homeworks_total = 0;
  $homeworks_count = 0;
  foreach($homeworks as $k =>$hwGrade){
    if(!$hwGrade->isDropped()){
      $homeworks_total = $homeworks_total + $hwGrade->getFloat();
      $homeworks_count++;
    }
  }
  $homeworks_final = $homeworks_total / $homeworks_count;

  $computational_projects = array(
    new ComputationalProjectGrade(1,$_POST['comp1'],18),
    new ComputationalProjectGrade(2,$_POST['comp2'],26),
  );
  $computational_projects_total = 0;
  $computational_projects_count = 0;
  foreach($computational_projects as $k =>$compGrade){
    $computational_projects_total = $computational_projects_total + $compGrade->getFloat();
    $computational_projects_count++;
  }
  $computational_projects_final = $computational_projects_total / $computational_projects_count;

  $exams = array(
    new ExamGrade(1,$_POST['exam1'],115),
    new ExamGrade(2,$_POST['exam2'],100),
    new ExamGrade(3,$_POST['exam3'],100),
  );

  usort($exams,"ExamGrade::compare_sort_by_score");
  $exams[0]->drop();
  usort($exams,"ExamGrade::compare_sort_by_index");
  $exams_total = 0;
  $exams_count = 0;
  foreach($exams as $k =>$examGrade){
    if(!$examGrade->isDropped()){
      $exams_total = $exams_total + $examGrade->getFloat();
      $exams_count++;
    }
  }
  $exams_final = $exams_total / $exams_count;

  $clicker_questions =  new Grade($_POST['clicker_questions'],100);
  $reciation_participation = new Grade($_POST['recitation_participation'],100);
  $facebook_participation = $_POST['facebook_participation'] ? 1 : 0 ;
  $exceptional_reciation_participation = $_POST['exceptional_reciation_participation'] ? 1 : 0 ;

  $summary = array(
    array('name'=>'Exams','ratio' => '0.5','value'=>$exams_final),
    array('name'=>'Homeworks','ratio' => '0.3','value'=>$homeworks_final),
    array('name'=>'Computational Projects','ratio' => '0.1','value'=>$computational_projects_final),
    array('name'=>'Recitation Attendance','ratio' => '0.05','value'=>$reciation_participation->getFloat()),
    array('name'=>'Class Participation','ratio' => '0.05','value'=>$clicker_questions->getFloat()),
    array('name'=>'Facebook Participation','ratio' => '0.02','value'=>$facebook_participation),
    array('name'=>'Exceptional Reciation Participation','ratio' => '0.02','value'=>$exceptional_reciation_participation),
  );

  $total = 0;
  foreach($summary as $k => $item){
    $total = $total + $item['ratio'] * $item['value'];
  }
?>
<html lang="en-us">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PHY 211 Calcultor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#" style="color:rgb(207,35,43);"><strong>PHY 211 Calculator</strong></a>
        </div>
      </div>
    </nav>
    <div class="jumbotron" style="margin-top:40px;">
      <div class="container">
        <h1>Grade Report</h1>
        <p>For Reference Only</p>
    </div>
    </div>
    <div class="container">
      <h3>Summary</h3>
      <h4>Your Letter Grade</h4>
      <p style="color:red;">Grade cut ups are from syllabus, just for reference.</p>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Cut Up</th>
            <th>Your Grade</th>
          </tr>
        </thead>
        <?php
          $cut_up = array(
            'A' => 90,
            'A-' => 85,
            'B+' => 80,
            'B' => 76,
            'B-' => 72,
            'C+' => 68,
            'C' => 64,
            'C-' => 60,
            'D' => 52,
            'F' => 0,
          );
          $letter_not_met = true;
        ?>
        <tbody>
          <?php foreach($cut_up as $letter => $limit):?>
            <?php if(100 * $total >= $limit && $letter_not_met): ?>
            <?php $letter_not_met = false;?>
              <tr class="success">
                <th><?php echo $letter;?></th>
                <th><?php echo $limit;?></th>
                <th><?php printf("%.2f",100 * $total);?></th>
              </tr>
            <?php else: ?>
              <tr>
                <td><?php echo $letter;?></td>
                <td><?php echo $limit;?></td>
                <td></td>
              </tr>
            <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <h4>Score Summary</h4>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Item</th>
            <th>Score</th>
            <th>Ratio</th>
            <th>Weighted Score</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($summary as $k => $item):?>
            <tr>
              <td><?php echo $item['name'];?></td>
              <td><?php printf("%.2f",100 * $item['value']);?>%</td>
              <td><?php printf("%d",100 * $item['ratio']);?>%</td>
              <td><?php printf("%.2f",100 * $item['ratio'] * $item['value']);?>%</td>
            </tr>
          <?php endforeach;?>
          <tr>
            <th>Total</th>
            <td></td>
            <td></td>
            <th><?php printf("%.2f",100 * $total);?>%</th>
          </tr>
        </tbody>
      </table>
      <h3>Performance Details</h3>
      <h4>Exams</h4>
      <p><span style="color:red;">Exams in red are dropped from calculation.</span></p>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Raw Score</th>
            <th>Available Score</th>
            <th>Score (%)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($exams as $k => $exam): ?>
            <tr <?php if($exam->isDropped()):?>class="danger"<?php endif; ?>>
              <td><?php echo $exam->getIndex();?></td>
              <td><?php echo $exam->getScore();?></td>
              <td><?php echo $exam->getFullScore();?></td>
              <td><?php printf("%.2f",$exam->getPercentage());?>%</td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <th>Total</th>
            <td></td>
            <td></td>
            <th><?php printf("%.2f",100 * $exams_final); ?>%</th>
          </tr>
        </tbody>
      </table>
      <h4>Homeworks</h4>
      <p><span style="color:red;">Homework items in red are dropped from calculation.</span></p>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Raw Score</th>
            <th>Available Score</th>
            <th>Score (%)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($homeworks as $k => $hw): ?>
            <tr <?php if($hw->isDropped()):?>class="danger"<?php endif; ?>>
              <td><?php echo $hw->getIndex();?></td>
              <td><?php echo $hw->getScore();?></td>
              <td><?php echo $hw->getFullScore();?></td>
              <td><?php printf("%.2f",$hw->getPercentage());?>%</td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <th>Total</th>
            <td></td>
            <td></td>
            <th><?php printf("%.2f",100 * $homeworks_final); ?>%</th>
          </tr>
        </tbody>
      </table>
      <h4>Computational Projects</h4>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Raw Score</th>
            <th>Available Score</th>
            <th>Score (%)</th>
          </tr>
        </thead>
        <tbody>
          </tr>
          <?php foreach($computational_projects as $k => $comp): ?>
            <tr>
              <td><?php echo $comp->getIndex();?></td>
              <td><?php echo $comp->getScore();?></td>
              <td><?php echo $comp->getFullScore();?></td>
              <td><?php printf("%.2f",$comp->getPercentage());?>%</td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <th>Total</th>
            <td></td>
            <td></td>
            <th><?php printf("%.2f",100 * $computational_projects_final); ?>%</th>
          </tr>
        </tbody>
      </table>
      <h4>Other Items</h4>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Raw Score</th>
            <th>Available Score</th>
            <th>Score (%)</th>
          </tr>
        </thead>
          <tr>
            <td>Recitation Participation</td>
            <td><?php echo $reciation_participation->getScore(); ?></td>
            <td><?php echo $reciation_participation->getFullScore(); ?></td>
            <td><?php printf("%d",$reciation_participation->getPercentage()); ?>%</td>
          </tr>
          <tr>
            <td>Clicker Questions</td>
            <td><?php echo $clicker_questions->getScore(); ?></td>
            <td><?php echo $clicker_questions->getFullScore(); ?></td>
            <td><?php printf("%d",$clicker_questions->getPercentage()); ?>%</td>
          </tr>
          <tr>
            <td>Facebook Participation Extra</td>
            <td>N/A</td>
            <td>N/A</td>
            <td><?php echo (100 * $facebook_participation); ?>%</td>
          </tr>
          <tr>
            <td>Exceptional Reciation Participation</td>
            <td>N/A</td>
            <td>N/A</td>
            <td><?php echo (100 * $exceptional_reciation_participation); ?>%</td>
          </tr>
        <tbody>
        </tbody>
      </table>
      <hr />
    </div>
    <footer style="text-align:center;margin-bottom:20px;">
      <p>&copy;2016&nbsp;Yinan Zhou</p>
    </footer>
  </body>
</html>
