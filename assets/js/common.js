// Web conponents
// クラスの定義
class MyElement extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.textContent = "This is My Element!";
  }
}

// カスタム要素の登録
customElements.define("my-element", MyElement);

// ヘッダーをWebコンポーネントで追加
class MyHeader extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.innerHTML = `
    <header class="l-header js-header" id="header">
        <div class="l-header__inner">
          <a class="l-header__logoLink" href="index.html#">
            <img
              class="l-header__logoImg"
              src="./assets/images/logo/lit.png"
              alt="ダミーロゴ"
            />
          </a>
          <nav class="l-header__nav js-hamburgerToggle">
            <div>
              <ul class="l-header__navItems">
                <li class="l-header__navItem">
                  <a class="l-header__navLink js-spLink" href="index.html#about"
                    >ABOUT US
                    <span class="l-header__navLink--jp">私たちについて</span>
                  </a>
                </li>
                <li class="l-header__navItem">
                  <a
                    class="l-header__navLink js-spLink"
                    href="index.html#message"
                    >MESSAGE
                    <span class="l-header__navLink--jp">メッセージ</span>
                  </a>
                </li>
                <li class="l-header__navItem">
                  <a
                    class="l-header__navLink js-spLink"
                    href="index.html#document"
                    >DOCUMENT
                    <span class="l-header__navLink--jp">採用ピッチ資料</span>
                  </a>
                </li>
                <li class="l-header__navItem">
                  <a
                    class="l-header__navLink js-spLink"
                    href="index.html#strengths"
                    >STRENGTHS
                    <span class="l-header__navLink--jp">働くポイント</span>
                  </a>
                </li>
                <li class="l-header__navItem">
                  <a
                    class="l-header__navLink js-spLink"
                    href="index.html#interview"
                    >INTERVIEW
                    <span class="l-header__navLink--jp">社員インタビュー</span>
                  </a>
                </li>
                <li class="l-header__navItem">
                  <a
                    class="l-header__navLink js-spLink"
                    href="index.html#recruit"
                    >RECRUIT INFO
                    <span class="l-header__navLink--jp">募集要項</span>
                  </a>
                </li>
              </ul>
            </div>
          </nav>
          <div class="l-header__hamburger js-hamburgerBtn">
            <span class="l-header__hamburgerLine js-hamburgerToggle"></span>
            <span class="l-header__hamburgerLine js-hamburgerToggle"></span>
            <span class="l-header__hamburgerLine js-hamburgerToggle"></span>
          </div>
        </div>
      </header>
    `;
  }
}

customElements.define("my-header", MyHeader);

// ハンバーガーメニューの開閉
const hamburgerBtn = document.querySelector(".js-hamburgerBtn");
const hamburgerToggles = document.querySelectorAll(".js-hamburgerToggle");

hamburgerBtn.addEventListener("click", () => {
  hamburgerToggles.forEach((hamburgerToggle) => {
    hamburgerToggle.classList.toggle("js-spMenuActive");
  });
});

// スマホのリンクをクリック時にメニューを閉じる
const spLinks = document.querySelectorAll(".js-spLink");

spLinks.forEach((spLink) => {
  spLink.addEventListener("click", () => {
    hamburgerToggles.forEach((hamburgerToggle) => {
      hamburgerToggle.classList.remove("js-spMenuActive");
    });
  });
});

// ヘッダー固定
window.addEventListener("scroll", () => {
  const header = document.querySelector(".js-header");
  const scroll = window.scrollY;
  const firstView = document.querySelector(".js-hero");
  if (firstView) {
    const firstViewHeight = firstView.offsetHeight;
    if (scroll > firstViewHeight) {
      header.style.position = "fixed";
      header.classList.add("js-animationHeader");
    } else {
      header.style.position = "absolute";
      header.classList.remove("js-animationHeader");
    }
  } else {
    header.style.position = "fixed";
  }
});
