document.querySelectorAll('.submit-feedback').forEach(button => {
    button.addEventListener('click', (e) => {
        const complaintId = e.target.getAttribute('data-id');
        const feedbackInput = document.querySelector(`.feedback[data-id='${complaintId}']`);
        const feedbackText = feedbackInput.value;

        if (feedbackText) {
            fetch('submit_feedback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(complaintId)}&feedback=${encodeURIComponent(feedbackText)}`
            })
            .then(response => {
                // Check if the response is OK
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data); // Log the entire response for debugging

                if (data.success) {
                    alert('Feedback submitted successfully!');
                    feedbackInput.value = ''; // Clear input field

                    // Update status and action fields
                    const complaintRow = e.target.closest('tr');
                    complaintRow.querySelector('.status-cell').textContent = 'Resolved'; // Update status
                    complaintRow.querySelector('.action-cell').textContent = feedbackText; // Update action with feedback

                    // Disable the feedback input and submit button
                    feedbackInput.disabled = true; // Disable input
                    e.target.disabled = true; // Disable submit button
                    e.target.style.display = 'none'; // Hide submit button

                    console.log('Feedback input disabled:', feedbackInput.disabled); // Debugging log
                    console.log('Submit button hidden:', e.target.style.display); // Debugging log
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error submitting feedback:', error);
               
            });
        } else {
            alert('Please enter feedback before submitting.');
        }
    });
});
