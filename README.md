# Web 　 components 触ってみた

## 開始方法

1. このリポジトリをクローンします。以上

## コードの説明

ヘッダーと main 内のテキストが Web components で作成してあります。

1. クラスの定義

```
class MyElement extends HTMLElement {
  constructor() {
    super();
  }
  connectedCallback() {
    this.textContent = "This is My Element!";
  }
}
```

2. カスタム要素の登録

```
customElements.define("my-element", MyElement);
```

3. 登録した要素の呼び出し

```
<my-element></my-element>
```

## 注意点

- common.js の中に Web components は記述してあります。
- 今後、メソッドなど勉強したら、更新予定
