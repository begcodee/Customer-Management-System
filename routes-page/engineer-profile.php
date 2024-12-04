<?php
// engineer-profile.php

// Database connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "authentication"; // Name of your database

// Create connection
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the complaints from the database
$sql = "SELECT * FROM contact"; // 'contact' is the table that stores the complaints
$result = $conn->query($sql);
$complaints = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Engineer Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal">

  <!-- Header Section -->
  <header class="bg-blue-600 text-white py-6 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
      <h1 class="text-3xl font-bold">Engineer Profile</h1>
      <nav>
        <a href="/Ocs/auth/login.html" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">Log Out</a>
      </nav>
    </div>
  </header>

  <!-- Main Section -->
  <main class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Welcome, Engineer</h2>

    <div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
      <!-- View Complaints Tab -->
      <div class="tab">
        <button id="viewComplaintsButton" onclick="toggleDropdown('viewComplaints')" 
        class="w-full text-lg font-medium text-left py-3 px-5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none transition">
          View Complaints
        </button>
        <div id="viewComplaints" class="dropdown-content hidden mt-4">
          <!-- Complaints Table -->
          <?php if (count($complaints) > 0): ?>
            <table class="table-auto w-full border-collapse border border-gray-300">
              <thead>
                <tr>
                  <th class="border px-4 py-2">Name</th>
                  <th class="border px-4 py-2">Email</th>
                  <th class="border px-4 py-2">Subject</th>
                  <th class="border px-4 py-2">Message</th>
                  <th class="border px-4 py-2">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($complaints as $complaint): ?>
                  <form method="POST" action="/Ocs/php/update_status.php">
                    <tr>
                      <td class="border px-4 py-2"><?= htmlspecialchars($complaint['name']) ?></td>
                      <td class="border px-4 py-2"><?= htmlspecialchars($complaint['email']) ?></td>
                      <td class="border px-4 py-2"><?= htmlspecialchars($complaint['subject']) ?></td>
                      <td class="border px-4 py-2"><?= nl2br(htmlspecialchars($complaint['message'])) ?></td>
                      <td class="border px-4 py-2"><?= htmlspecialchars($complaint['Status']) ?></td>
                          <td class="border px-4 py-2">
                            <form action="update_status.php" method="POST">
                              <!-- Hidden input field for the complaint's email -->
                              <input type="hidden" name="email" value="<?= htmlspecialchars($complaint['email']) ?>">
                              
                              <td>
                              <!-- Dropdown for selecting the status -->
                              <select name="status" class="border px-4 py-2 rounded-lg">
                                <option value="pending" <?= $complaint['Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="attended" <?= $complaint['Status'] == 'Attended' ? 'selected' : '' ?>>Attended</option>
                              </select>
                              </td>
                              
                              
                              <td>
                              <!-- Submit button for updating status -->
                              <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Update Status</button>
                             </td>
                            </form>
                          </td>
                    </tr>
                  </form>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p class="text-gray-600">No complaints found.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Toggle the dropdown visibility when the "View Complaints" button is clicked
    function toggleDropdown(id) {
      const element = document.getElementById(id);
      if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
      } else {
        element.classList.add('hidden');
      }
    }
  </script>

</body>
</html>
