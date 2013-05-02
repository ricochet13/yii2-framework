<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\widgets;

use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\JsExpression;

/**
 * ActiveForm ...
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveForm extends Widget
{
	/**
	 * @param array|string $action the form action URL. This parameter will be processed by [[\yii\helpers\Html::url()]].
	 */
	public $action = '';
	/**
	 * @var string the form submission method. This should be either 'post' or 'get'.
	 * Defaults to 'post'.
	 */
	public $method = 'post';
	/**
	 * @var array the HTML attributes (name-value pairs) for the form tag.
	 * The values will be HTML-encoded using [[Html::encode()]].
	 * If a value is null, the corresponding attribute will not be rendered.
	 */
	public $options = array();
	/**
	 * @var array the default configuration used by [[field()]] when creating a new field object.
	 */
	public $fieldConfig = array(
		'class' => 'yii\widgets\ActiveField',
	);
	/**
	 * @var string the default CSS class for the error summary container.
	 * @see errorSummary()
	 */
	public $errorSummaryCssClass = 'error-summary';
	/**
	 * @var string the CSS class that is added to a field container when the associated attribute is required.
	 */
	public $requiredCssClass = 'required';
	/**
	 * @var string the CSS class that is added to a field container when the associated attribute has validation error.
	 */
	public $errorCssClass = 'error';
	/**
	 * @var string the CSS class that is added to a field container when the associated attribute is successfully validated.
	 */
	public $successCssClass = 'success';
	/**
	 * @var string the CSS class that is added to a field container when the associated attribute is being validated.
	 */
	public $validatingCssClass = 'validating';
	/**
	 * @var boolean whether to enable client-side data validation.
	 * If [[ActiveField::enableClientValidation]] is set, its value will take precedence for that input field.
	 */
	public $enableClientValidation = true;
	/**
	 * @var boolean whether to enable AJAX-based data validation.
	 * If [[ActiveField::enableAjaxValidation]] is set, its value will take precedence for that input field.
	 */
	public $enableAjaxValidation = false;
	/**
	 * @var array|string the URL for performing AJAX-based validation. This property will be processed by
	 * [[Html::url()]]. Please refer to [[Html::url()]] for more details on how to configure this property.
	 * If this property is not set, it will take the value of the form's action attribute.
	 */
	public $validationUrl;
	/**
	 * @var boolean whether to perform validation when the form is submitted.
	 */
	public $validateOnSubmit = true;
	/**
	 * @var boolean whether to perform validation when an input field loses focus and its value is found changed.
	 * If [[ActiveField::validateOnChange]] is set, its value will take precedence for that input field.
	 */
	public $validateOnChange = false;
	/**
	 * @var boolean whether to perform validation while the user is typing in an input field.
	 * If [[ActiveField::validateOnType]] is set, its value will take precedence for that input field.
	 * @see validationDelay
	 */
	public $validateOnType = false;
	/**
	 * @var integer number of milliseconds that the validation should be delayed when a user is typing in an input field.
	 * This property is used only when [[validateOnType]] is true.
	 * If [[ActiveField::validationDelay]] is set, its value will take precedence for that input field.
	 */
	public $validationDelay = 200;
	/**
	 * @var JsExpression|string a [[JsExpression]] object or a JavaScript expression string representing
	 * the callback that will be invoked BEFORE validating EACH attribute on the client side.
	 * The signature of the callback should be like the following:
	 *
	 * ~~~
	 * ~~~
	 */
	public $beforeValidateAttribute;
	/**
	 * @var JsExpression|string a [[JsExpression]] object or a JavaScript expression string representing
	 * the callback that will be invoked AFTER validating EACH attribute on the client side.
	 * The signature of the callback should be like the following:
	 *
	 * ~~~
	 * ~~~
	 */
	public $afterValidateAttribute;
	/**
	 * @var JsExpression|string a [[JsExpression]] object or a JavaScript expression string representing
	 * the callback that will be invoked BEFORE validating ALL attributes on the client side when the validation
	 * is triggered by form submission (that is, [[validateOnSubmit]] is set true).
	 *
	 * This callback is called before [[beforeValidateAttribute]].
	 *
	 * The signature of the callback should be like the following:
	 *
	 * ~~~
	 * ~~~
	 */
	public $beforeValidate;
	/**
	 * @var JsExpression|string a [[JsExpression]] object or a JavaScript expression string representing
	 * the callback that will be invoked AFTER validating ALL attributes on the client side when the validation
	 * is triggered by form submission (that is, [[validateOnSubmit]] is set true).
	 *
	 * This callback is called after [[afterValidateAttribute]].
	 *
	 * The signature of the callback should be like the following:
	 *
	 * ~~~
	 * ~~~
	 */
	public $afterValidate;
	/**
	 * @var array list of attributes that need to be validated on the client side. Each element of the array
	 * represents the validation options for a particular attribute.
	 * @internal
	 */
	public $attributes = array();

	/**
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
		echo Html::beginForm($this->action, $this->method, $this->options);
	}

	/**
	 * Runs the widget.
	 * This registers the necessary javascript code and renders the form close tag.
	 */
	public function run()
	{
		if ($this->attributes !== array()) {
			$id = $this->options['id'];
			$options = Json::encode($this->getClientOptions());
			$attributes = Json::encode($this->attributes);
			$this->view->registerAssetBundle('yii/form');
			$this->view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
		}
		echo Html::endForm();
	}

	/**
	 * Returns the options for the form JS widget.
	 * @return array the options
	 */
	protected function getClientOptions()
	{
		$options = array(
			'errorSummary' => '.' . $this->errorSummaryCssClass,
			'validateOnSubmit' => $this->validateOnSubmit,
			'errorCssClass' => $this->errorCssClass,
			'successCssClass' => $this->successCssClass,
			'validatingCssClass' => $this->validatingCssClass,
		);
		if ($this->validationUrl !== null) {
			$options['validationUrl'] = Html::url($this->validationUrl);
		}
		$callbacks = array(
			'beforeValidateAttribute',
			'afterValidateAttribute',
			'beforeValidate',
			'afterValidate',
		);
		foreach ($callbacks as $callback) {
			$value = $this->$callback;
			if (is_string($value)) {
				$value = new JsExpression($value);
			}
			if ($value instanceof JsExpression) {
				$options[$callback] = $value;
			}
		}

		return $options;
	}

	/**
	 * Generates a summary of the validation errors.
	 * If there is no validation error, an empty error summary markup will still be generated, but it will be hidden.
	 * @param Model|Model[] $models the model(s) associated with this form
	 * @param array $options the tag options in terms of name-value pairs. The following options are specially handled:
	 *
	 * - header: string, the header HTML for the error summary. If not set, a default prompt string will be used.
	 * - footer: string, the footer HTML for the error summary.
	 *
	 * The rest of the options will be rendered as the attributes of the container tag. The values will
	 * be HTML-encoded using [[encode()]]. If a value is null, the corresponding attribute will not be rendered.
	 * @return string the generated error summary
	 */
	public function errorSummary($models, $options = array())
	{
		if (!is_array($models)) {
			$models = array($models);
		}

		$lines = array();
		foreach ($models as $model) {
			/** @var $model Model */
			foreach ($model->getFirstErrors() as $error) {
				$lines[] = Html::encode($error);
			}
		}

		$header = isset($options['header']) ? $options['header'] : '<p>' . Yii::t('yii|Please fix the following errors:') . '</p>';
		$footer = isset($options['footer']) ? $options['footer'] : '';
		unset($options['header'], $options['footer']);

		if (!isset($options['class'])) {
			$options['class'] = $this->errorSummaryCssClass;
		} else {
			$options['class'] .= ' ' . $this->errorSummaryCssClass;
		}

		if ($lines !== array()) {
			$content = "<ul><li>" . implode("</li>\n<li>", $lines) . "</li><ul>";
			return Html::tag('div', $header . $content . $footer, $options);
		} else {
			$content = "<ul></ul>";
			$options['style'] = isset($options['style']) ? rtrim($options['style'], ';') . '; display:none' : 'display:none';
			return Html::tag('div', $header . $content . $footer, $options);
		}
	}

	/**
	 * Generates a form field.
	 * A form field is associated with a model and an attribute. It contains a label, an input and an error message
	 * and use them to interact with end users to collect their inputs for the attribute.
	 * @param Model $model the data model
	 * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
	 * about attribute expression.
	 * @param array $options the additional configurations for the field object
	 * @return ActiveField the created ActiveField object
	 * @see fieldConfig
	 */
	public function field($model, $attribute, $options = array())
	{
		return Yii::createObject(array_merge($this->fieldConfig, $options, array(
			'model' => $model,
			'attribute' => $attribute,
			'form' => $this,
		)));
	}
}
