<div>
    <!-- Name Collection Modal -->
    <div class="modal fade" id="nameCollectionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title d-grid text-center w-100">
                        <span id="typing-text" class=" text-primary d-block"></span>
                    </h6>
                </div>
                <div class="modal-body">
                    <form id="nameCollectionForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">نام</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">نام خانوادگی</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveUserNames()">ذخیره</button>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::check() && (!Auth::user()->first_name || !Auth::user()->last_name))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in and has no name
            const nameCollectionModal = new bootstrap.Modal(document.getElementById('nameCollectionModal'));
            nameCollectionModal.show();

            // Typing animation
            const text = "سلام\nبه دنیز خوش اومدی\n دستیار هستم و از آموزش تا اشتغال کنارتم\nاسمت رو وارد کن تا بهتر بتونم کمکت کنم";
            const typingText = document.getElementById('typing-text');
            let i = 0;
            let currentLine = '';
            let lines = text.split('\n');
            let currentLineIndex = 0;

            function typeWriter() {
                if (currentLineIndex < lines.length) {
                    if (i < lines[currentLineIndex].length) {
                        currentLine += lines[currentLineIndex].charAt(i);
                        typingText.innerHTML = currentLine;
                        i++;
                        setTimeout(typeWriter, 40);
                    } else {
                        currentLine += '<br>';
                        typingText.innerHTML = currentLine;
                        currentLineIndex++;
                        i = 0;
                        setTimeout(typeWriter, 200);
                    }
                }
            }

            typeWriter();
        });

        function saveUserNames() {
            const form = document.getElementById('nameCollectionForm');
            const formData = new FormData(form);

            fetch('{{ route("user.update-names") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const nameCollectionModal = bootstrap.Modal.getInstance(document.getElementById('nameCollectionModal'));
                        nameCollectionModal.hide();
                        // Reload the page to show updated name
                        window.location.reload();
                    } else {
                        alert('خطا در ذخیره اطلاعات');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('خطا در ذخیره اطلاعات');
                });
        }
    </script>
    @endif
</div>