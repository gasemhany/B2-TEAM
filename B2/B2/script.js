// الانتظار حتى يتم تحميل المحتوى بالكامل قبل إضافة الأحداث
document.addEventListener("DOMContentLoaded", () => {
    // تحديد جميع الأزرار التي تحتوي على فئة "buy-btn"
    const buttons = document.querySelectorAll(".buy-btn");

    // إضافة حدث click على كل زر من الأزرار المحددة
    buttons.forEach(button => {
        button.addEventListener("click", () => {
            // عرض رسالة منبثقة عند الضغط على زر "شراء"
            alert("Thank you for your purchase!");
        });
    });
});

// الانتظار حتى يتم تحميل المحتوى بالكامل قبل إضافة الأحداث
document.addEventListener("DOMContentLoaded", () => {
    // تحديد جميع العناصر التي تحتوي على فئة "animate"
    const elements = document.querySelectorAll(".animate");

    // إنشاء ملاحظ (Observer) لاكتشاف متى تظهر العناصر في نافذة العرض
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // إذا كانت العنصر مرئيًا في نافذة العرض، نضيف فئة "visible" لجعل العنصر يظهر
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            } else {
                // إذا كان العنصر غير مرئي، نزيل فئة "visible" لإخفائه
                entry.target.classList.remove("visible");
            }
        });
    }, { threshold: 0.2 }); // تعيين العتبة ليتم تفعيل التأثير عندما يظهر 20% من العنصر في نافذة العرض

    // ملاحظة جميع العناصر لتتبع ظهورها
    elements.forEach(element => observer.observe(element));
});

// الانتظار حتى يتم تحميل المحتوى بالكامل قبل إضافة الأحداث
document.addEventListener("DOMContentLoaded", () => {
    // تحديد جميع الروابط داخل قائمة التنقل في الرأس
    const links = document.querySelectorAll("header .nav-links a");

    // إضافة تأثير التمرير السلس عند النقر على الروابط
    links.forEach(link => {
        link.addEventListener("click", (e) => {
            // منع الرابط من التوجيه الافتراضي
            e.preventDefault();

            // الحصول على معرّف العنصر الذي يشير إليه الرابط
            const targetId = link.getAttribute("href").substring(1);
            const targetElement = document.getElementById(targetId);

            // التمرير إلى العنصر المستهدف مع تعديل الإزاحة بسبب الهيدر الثابت
            window.scrollTo({
                top: targetElement.offsetTop - 60, // تخصيص المسافة المطلوبة لتناسب الهيدر الثابت
                behavior: "smooth" // التمرير السلس
            });
        });
    });
});
document.addEventListener("DOMContentLoaded", () => {
    const cartButton = document.querySelector(".cart-button");
    const cartDropdown = document.querySelector(".cart-dropdown");
    const addCartButtons = document.querySelectorAll(".add-cart");
    const cartCount = document.querySelector(".cart-count");
    const cartItems = document.querySelector(".cart-items");

    let cart = [];

    addCartButtons.forEach(button => {
        button.addEventListener("click", () => {
            const name = button.dataset.name;
            const price = button.dataset.price;

            cart.push({ name, price });
            updateCart();
        });
    });

    cartButton.addEventListener("mouseenter", () => {
        cartDropdown.style.display = "block";
    });

    cartButton.addEventListener("mouseleave", () => {
        cartDropdown.style.display = "none";
    });

    function updateCart() {
        cartCount.textContent = cart.length;
        cartItems.innerHTML = "";

        cart.forEach(item => {
            const li = document.createElement("li");
            
            cartItems.appendChild(li);
        });
    }
});