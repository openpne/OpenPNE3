<h1>テンプレートの書式</h1>

<h2>変数</h2>

<p><code>{{ ... }}</code> と記述することで、括弧に囲まれた変数を表示します。</p>

<p>変数が配列である場合は、 <code>.</code> (ドット) の後に続けて要素名を記述するか、もしくは PHP の配列のように <code>[ ]</code> (角括弧) を続け、括弧内に要素名を記述することで変数を表示できます。</p>

<p>そのテンプレート内で使用可能な変数は、各テンプレート編集ページの下部に一覧してあります。</p>

<div class="example">
<h3>書式例</h3>
<pre><code>
{{ foo }}  // foo という変数を表示する
{{ foo.bar }}  // foo という配列の bar という要素を表示する
{{ foo['bar'] }} // foo という配列の bar という要素を表示する
</code></pre>
</div>

<h2>条件分岐</h2>

<p>変数の内容によって表示するかしないかを分岐させるには、 <code>if</code> 構文を使用します。</p>
<p><code>if</code> 構文を終了させるには、 <code>endif</code> を使用します。</p>

<div class="example">
<h3>書式例</h3>
<p>以下は、 ID が 1 のメンバーの場合に特定の文言を表示させるサンプルになります。</p>
<pre><code>
{% if member.id == "1" %}
管理者のメンバーです。
{% endif %}
</code></pre>
</div>

<h2>ループ</h2>

<p>配列の各要素に対して繰り返しの処理をおこないたい場合は、 <code>for</code> 構文を使用します。</p>
<p>members という配列の各要素に対して member という名前でアクセスしたい場合は <code>{% for members in member %}</code> というような記述になります。</p>

<div class="example">
<h3>書式例</h3>
<p>以下は、各メンバーの情報を格納した members という配列の各要素からニックネームを取り出し、表示させるサンプルです。</p>
<pre><code>
&lt;ul&gt;
{% for members in member %}
&lt;li&gt;{{ member.name }}&lt;/li&gt;
{% endfor %}
&lt;/ul&gt;
</code></pre>
</div>

<h2>演算子</h2>
<p>以下の演算子が使用できます。</p>
<dl>
  <dt>+</dt>
  <dd>左辺と右辺の値を加算した結果を返します</dd>

  <dt>-</dt>
  <dd>左辺と右辺の値を減算した結果を返します</dd>

  <dt>/</dt>
  <dd>左辺と右辺の値を除算した結果を返します</dd>

  <dt>%</dt>
  <dd>左辺と右辺の値を剰余算した結果を返します</dd>

  <dt>*</dt>
  <dd>左辺と右辺の値を乗算した結果を返します</dd>

  <dt>and</dt>
  <dd>左辺と右辺が真のときに真を返します</dd>

  <dt>or</dt>
  <dd>左辺か右辺が真のときに真を返します</dd>

  <dt>==</dt>
  <dd>左辺と右辺が同一の値の場合に真を返します</dd>

  <dt>!=</dt>
  <dd>左辺と右辺が同一でない値の場合に真を返します</dd>

  <dt>&lt;</dt>
  <dd>左辺よりも右辺が大きい値の場合に真を返します</dd>

  <dt>&gt;</dt>
  <dd>右辺よりも左辺が大きい値の場合に真を返します</dd>

  <dt>&lt;=</dt>
  <dd>右辺の値が左辺以上の値の場合に真を返します</dd>

  <dt>&gt;=</dt>
  <dd>左辺の値が右辺以上の値の場合に真を返します</dd>
</dl>

<h2>フィルター</h2>

<p>フィルターは、変数に対してフィルタリングをするための機能です。 <code>foo</code> 変数に <code>date</code> フィルターを使用したい場合は、 <code>{{ foo|date }}</code> というように、変数に <code>|</code> とフィルターを続けて記述します。</p>

<p>以下のフィルターが使用できます。</p>

<dl>
  <dt>date</dt>
  <dd>変数を引数で指定された日付フォーマットでフィルタリングします。
    <div><pre><code>{{ member.config.lastLogin|date("Y年m月d日") }}</code></pre></div>
  </dd>

  <dt>default</dt>
  <dd>変数が未定義だった場合、引数で指定された値を返します。
    <div><pre><code>{{ undefined|default("デフォルト値") }}</code></pre></div>
  </dd>
</dl>

<h2>参考情報</h2>

<p>OpenPNE では Twig というテンプレートエンジンを使用しています。</p>

<p>より詳細な情報は <a href="http://www.twig-project.org/documentation" target="_blank">Twig</a> のドキュメントを参照してください。</p>

