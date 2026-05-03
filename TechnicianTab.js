const confirmModal = document.getElementById('confirmModal');
const confirmYes   = document.getElementById('confirmYes');
const confirmNo    = document.getElementById('confirmNo');

let targetJobId = null;

document.querySelectorAll('.delete-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    targetJobId = btn.dataset.id;
    confirmModal.style.display = 'flex';
  });
});

confirmYes.addEventListener('click', () => {
  if (!targetJobId) return;
  const form = document.createElement('form');
  form.method = 'post';
  form.action = 'TechnicianTab.php?view=complete';
  form.innerHTML = `
    <input type="hidden" name="job_id"   value="${targetJobId}">
    <input type="hidden" name="action"   value="delete">
  `;
  document.body.appendChild(form);
  form.submit();
});

confirmNo.addEventListener('click', () => {
  confirmModal.style.display = 'none';
  targetJobId = null;
});
