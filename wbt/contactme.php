<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contract Me</title>
    <link rel="stylesheet" href="../../portfolio/css/contactme.css">
  </head>
  <body>
    <nav>
      <ul>
        <li><a href="../../index.html">Home</a></li>
        <li><a href="Education.html">Education</a></li>
        <li><a href="Experience.html">Experience</a></li>
        <li><a href="Project.html">Project</a></li>
        <li><a href="contactme.html">Contract Me</a></li>
      </ul>
    </nav>
    <br>
    <form action="contactme.php" method="post">
      <fieldset>
        <legend>Registration Form</legend>

        <table>
          <tr>
            <td><label for="fname">First Name:</label></td>
            <td><input type="text" id="fname" name="fname" required /></td>
          </tr>
          <tr>
            <td><label for="lname">Last Name:</label></td>
            <td><input type="text" id="lname" name="lname" required /></td>
          </tr>
          <tr>
            <td><label>Gender:</label></td>
            <td>
              <input type="radio" name="gender" value="male" />Male
              <input type="radio" name="gender" value="female" />Female
            </td>
          </tr>
          <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" id="email" name="email" required /></td>
          </tr>
          <tr>
            <td><label>Reason Of Contract</label></td>
            <td>
              <input
                type="radio"
                name="Projects"
                value="Projects"
              />Projects
              <input
                type="radio"
                name="Thesis"
                value="Thesis"
              />Thesis
              <input type="radio" name="Job" value="Job" />Job
            </td>
          </tr>

          <tr>
            <td><label>Topics:</label></td>
            <td>
              <input type="checkbox" name="web" value="Web Development" required /> Web
              Development
              <input type="checkbox" name="mobile" value="Mobile Development" /> Mobile
              Development
              <input type="checkbox" name="ai" value="AI/ML Development" /> AI/ML
              Development
            </td>
          </tr>
          <tr>
            <td><label>Consultation date:</label></td>
            <td>
              <input type="date" id="start" name="Consultation-date" />
            </td>
          </tr>

          <tr>
            <td></td>
            <td>
              <input type="submit" name="confirm" value="confirm" />
              <input type="reset" />
            </td>
          </tr>
        </table>
      </fieldset>
    </form>

    <br />
     <footer>
      <p>&copy; 2024 Md. Rasel Hossain. All rights reserved.</p>
      <p>Contract</p>
      <p><a href="https://www.facebook.com/russel11h">Facebook Profile</a></p>
      <p><a href="https://github.com/russel11h">GitHub Profile</a></p>
      <p><strong>Phone Number:</strong> +8801712345678</p>
      <p><strong>Email:</strong> <em>raselhossain11@gmail.com </em></p>
    </footer>
  </body>
</html>

<?php
if (isset($_POST['confirm'])) 
  {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $consultationDate = $_POST['Consultation-date'];
    $reasonOfContract = $_POST['Projects'] ?? $_POST['Thesis'] ?? $_POST['Job'] ?? 'Not specified';
    $topics = [];

    if (isset($_POST['web'])) {
        $topics[] = $_POST['web'];
    }
    if (isset($_POST['mobile'])) {
        $topics[] = $_POST['mobile'];
    }
    if (isset($_POST['ai'])) {
        $topics[] = $_POST['ai'];
    }

    if (empty($fname) || empty($lname) || empty($gender) || empty($email) || empty($consultationDate) || empty($reasonOfContract) || empty($topics)) 
      {
        echo "Please fill in all required fields.";
    } else {
        echo "First Name: {$fname} <br>";
        echo "Last Name: {$lname} <br>";
        echo "Gender: {$gender}<br>";
        echo "Email: {$email} <br>";      
        echo "Reason of Contract: {$reasonOfContract} <br>";
        echo "Topics: { implode(", ", $topics) } <br>";
        echo "Consultation Date: {$consultationDate} <br>";
    }
 }
?>
