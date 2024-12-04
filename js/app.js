// Global Variables
const currentUser = JSON.parse(localStorage.getItem('currentUser'));
const currentEngineer = JSON.parse(localStorage.getItem('currentEngineer'));

// Toggle Dropdowns
function toggleDropdown(sectionId) {
  const dropdown = document.getElementById(sectionId);
  const isVisible = dropdown.style.display === 'block';

  // Hide all dropdowns
  document.querySelectorAll('.dropdown-content').forEach(content => {
    content.style.display = 'none';
  });

  // Toggle visibility of selected dropdown
  if (!isVisible) {
    dropdown.style.display = 'block'; // Show the selected dropdown
    if (sectionId === 'viewAllComplaints') loadAllComplaints(); // Load complaints for engineers
    if (sectionId === 'viewComplaints') loadUserComplaints(); // Load complaints for users
  }
}


// Place a Complaint (User)
function placeComplaint() {
  const complaintText = document.getElementById('complaintText').value.trim();
  if (!complaintText) {
    alert('Please describe your issue.');
    return;
  }

  const complaintID = `${currentUser.id}-${Date.now()}`;
  const newComplaint = {
    ComplaintID: complaintID,
    UserName: currentUser.name,
    ComplaintText: complaintText,
    Status: 'Unattended',
  };

  const complaints = JSON.parse(localStorage.getItem('complaints')) || [];
  complaints.push(newComplaint);
  localStorage.setItem('complaints', JSON.stringify(complaints));

  alert(`Complaint submitted! Your Complaint ID is: ${complaintID}`);
  loadUserComplaints();
}

// Load Complaints for Users
function loadUserComplaints() {
  const complaints = JSON.parse(localStorage.getItem('complaints')) || [];
  const userComplaints = complaints.filter(complaint => complaint.UserName === currentUser.name);

  let content = '<h3>Your Complaints</h3><ul>';
  userComplaints.forEach(complaint => {
    content += `
      <li>
        Complaint ID: ${complaint.ComplaintID}, Status: ${complaint.Status}<br>
        Description: ${complaint.ComplaintText}<br>
        <button class="btn" onclick="removeComplaint('${complaint.ComplaintID}')">Remove</button>
      </li>
    `;
  });
  content += '</ul>';

  document.getElementById('complaintHistory').innerHTML = content;
}

// Remove Complaint
function removeComplaint(complaintID) {
  let complaints = JSON.parse(localStorage.getItem('complaints')) || [];
  complaints = complaints.filter(complaint => complaint.ComplaintID !== complaintID);
  localStorage.setItem('complaints', JSON.stringify(complaints));

  alert(`Complaint ${complaintID} has been removed.`);
  loadUserComplaints(); // Refresh user complaints
}


// Load All Complaints (Engineer)
function loadAllComplaints() {
  const complaints = JSON.parse(localStorage.getItem('complaints')) || [];

  let content = '<h3>All Complaints</h3><ul>';
  complaints.forEach(complaint => {
    content += `
      <li>
        Complaint ID: ${complaint.ComplaintID}, User: ${complaint.UserName}, Status: ${complaint.Status}<br>
        Description: ${complaint.ComplaintText}<br>
        ${getEngineerActionButtons(complaint)}
      </li>
    `;
  });
  content += '</ul>';

  document.getElementById('allComplaints').innerHTML = content;
}

// Generate Action Buttons for Engineers
function getEngineerActionButtons(complaint) {
  if (complaint.Status === 'Unattended') {
    return `<button class="btn" onclick="updateComplaintStatus('${complaint.ComplaintID}', 'In Progress')">Mark as In Progress</button>`;
  }
  if (complaint.Status === 'In Progress') {
    return `<button class="btn" onclick="updateComplaintStatus('${complaint.ComplaintID}', 'Addressed')">Mark as Addressed</button>`;
  }
  return '';
}

// Update Complaint Status (Engineer)
function updateComplaintStatus(complaintID, newStatus) {
  const complaints = JSON.parse(localStorage.getItem('complaints')) || [];
  const complaint = complaints.find(c => c.ComplaintID === complaintID);
  if (complaint) {
    complaint.Status = newStatus;
    localStorage.setItem('complaints', JSON.stringify(complaints));
    alert(`Complaint ${complaintID} marked as ${newStatus}.`);
    loadAllComplaints(); // Refresh the complaint list
  }
}

// Initialize Profiles
document.addEventListener("DOMContentLoaded", function () {
  if (currentUser) {
    const userNameElement = document.getElementById('userName');
    if (userNameElement) {
      userNameElement.textContent = currentUser.name || "User"; // Display user name
      loadUserComplaints(); // Load user-specific complaints
    } else {
      console.error("User name element not found.");
    }
  }

  if (currentEngineer) {
    const engineerNameElement = document.getElementById('engineerName');
    if (engineerNameElement) {
      engineerNameElement.textContent = currentEngineer.name || "Engineer"; // Display engineer name
      loadAllComplaints(); // Load all complaints for engineers
    } else {
      console.error("Engineer name element not found.");
    }
  }

  if (!currentUser && !currentEngineer) {
    console.error("No user or engineer is logged in.");
  }
});



console.log("Engineer Name Element:", document.getElementById('engineerName'));
console.log("Current Engineer:", currentEngineer);
console.log("HTML Content:", document.body.innerHTML);

const link = new URL('ocs/php/get_complaints.php'); // Define the URL for the PHP script

function getCompliants() {
  // Perform AJAX request to fetch complaints from the server
  fetch(link) // Pass the link directly without curly braces
    .then(response => response.text())
    .then(data => {
      // Display the returned complaints in the container
      document.getElementById('complaintContainer').innerHTML = data;
    })
    .catch(error => {
      console.error('Error fetching complaints:', error);
    });
};
