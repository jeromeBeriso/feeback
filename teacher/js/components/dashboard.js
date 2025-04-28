function booking_analytics(session_id) {
  fetch('ajax/dashboard.php?session_id=' + session_id)
      .then(res => res.json())
      .then(data => {
 
          document.querySelector('#positive-count').textContent = data.total_positive;
          document.querySelector('#negative-count').textContent = data.total_negative;
          document.querySelector('#neutral-count').textContent = data.total_neutral;
          document.querySelector('#verdict-percent').textContent = data.verdict_percentage + '%';
          document.querySelector('#new-feedback').textContent = data.new_feedback;
          document.querySelector('#total-feedback').textContent = data.total_feedbacks;
          document.querySelector('#students-count').textContent = data.students_count;
          document.querySelector('#dynamic-message').textContent = data.message;
      });
}
