<div class="oath-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
<form method="post">
    <label>Do You Authorize TestClient?</label><br />
    <input type="submit" name="authorized" value="yes">
    <input type="submit" name="authorized" value="no">
</form>