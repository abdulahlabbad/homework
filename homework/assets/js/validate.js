// validate.js — AJAX Form Validation لفورم إضافة الطالب
// الفكرة: منبعت قيم الحقول لـ validate_student.php بالخلفية (بدون تحديث الصفحة)
// والسيرفر بيرجع JSON بالأخطاء، ومنعرضها تحت كل حقل.

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('studentForm');
    if (!form) return;

    const fields = ['name', 'email', 'phone', 'address'];

    // عرض/إخفاء الأخطاء على الحقول
    function showErrors(errors, onlyField = null) {
        const targets = onlyField ? [onlyField] : fields;
        targets.forEach(field => {
            const input = document.getElementById(field);
            const feedback = form.querySelector(`.invalid-feedback[data-for="${field}"]`);
            if (errors[field]) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                feedback.textContent = errors[field];
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedback.textContent = '';
            }
        });
    }

    // طلب AJAX باستخدام fetch
    async function validateOnServer() {
        const data = new FormData();
        fields.forEach(f => data.append(f, document.getElementById(f).value));

        const response = await fetch('validate_student.php', {
            method: 'POST',
            body: data,
        });
        if (!response.ok) throw new Error('Validation request failed');
        return response.json(); // { valid: true/false, errors: {...} }
    }

    // تحقق لحظي: لما المستخدم يطلع من الحقل (blur)
    fields.forEach(field => {
        document.getElementById(field).addEventListener('blur', async () => {
            try {
                const result = await validateOnServer();
                showErrors(result.errors, field);
            } catch (e) {
                console.error(e);
            }
        });
    });

    // عند الإرسال: منتحقق بالـ AJAX أولاً، وإذا كلشي تمام منبعت الفورم (مع الصورة)
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;

        try {
            const result = await validateOnServer();
            showErrors(result.errors);
            if (result.valid) {
                form.submit(); // إرسال عادي حتى تنرفع الصورة وينحفظ الطالب
                return;
            }
        } catch (e) {
            // إذا فشل الـ AJAX منكمّل بالإرسال، والسيرفر رح يتحقق كمان مرة
            form.submit();
            return;
        }
        btn.disabled = false;
    });

    // معاينة الصورة قبل الرفع + فحص سريع للحجم
    const fileInput = document.getElementById('profile_picture');
    const preview = document.getElementById('preview');
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        const feedback = form.querySelector('.invalid-feedback[data-for="profile_picture"]');
        fileInput.classList.remove('is-invalid');
        feedback.textContent = '';
        preview.classList.add('d-none');

        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            fileInput.classList.add('is-invalid');
            feedback.textContent = 'Image must be smaller than 2MB.';
            fileInput.value = '';
            return;
        }
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    });
});
