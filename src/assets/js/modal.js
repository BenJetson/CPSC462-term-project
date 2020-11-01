/* TODO it would be nice if the CSS transitions worked here.
 *      at the moment, they don't work because we are changing the display
 *      property, which is not eligible for CSS transition.
 *
 *      The reference implementation from Bootstrap in jQuery does some things
 *      with the opacity I think, check the fade class for details.
 */

/**
 * Modal is a vanilla JavaScript wrapper for Bootstrap modals. I analysed what
 * the jQuery was doing to the DOM nodes and emulated it here.
 */
class Modal {
  constructor(id) {
    this.node = document.getElementById(id);
    if (!this.node) throw `no such node with id '${id}'`;
    else if (!this.node.classList.contains("modal"))
      throw `node with id '${id}' does not appear to be a modal`;
    else if (!this.node.classList.contains("d-none"))
      throw `modal with id '${id}' is not hidden by default`;
    else if (this.node.classList.contains("show"))
      throw `modal with id '${id}' has show by default`;

    this.backdropNode = null;
    this.node.setAttribute("tabIndex", -1);
    this.hide();

    // Let dismiss and close buttons within the modal's DOM tree fire the
    // hide method via an event listener.
    for (const btn of [
      this.node,
      this.node.querySelector("button.close"),
      ...this.node.querySelectorAll("button.btn-dismiss"),
    ]) {
      btn?.addEventListener("click", (event) => this.hide(btn, event));
    }
  }

  show() {
    this.node.classList.add("d-block", "show");
    this.node.classList.remove("d-none");
    this.node.setAttribute("aria-hidden", false);
    this.node.setAttribute("aria-modal", true);
    this.node.setAttribute("role", "dialog");

    if (!this.backdropNode) {
      this.backdropNode = document.createElement("div");
      this.backdropNode.classList.add("modal-backdrop", "fade", "show");
      document.body.appendChild(this.backdropNode);
    }
  }

  hide(src = null, event = null) {
    if (event && event.target !== src) {
      // Ensure that the backdrop click event does not close the modal
      // if the target of the click was not the backdrop itself.
      // Inspired by: https://stackoverflow.com/a/9183467
      return;
    }

    this.node.classList.add("d-none");
    this.node.classList.remove("d-block", "show");
    this.node.setAttribute("aria-hidden", true);
    this.node.removeAttribute("aria-modal");
    this.node.removeAttribute("role");

    if (this.backdropNode) {
      document.body.removeChild(this.backdropNode);
      this.backdropNode = null;
    }
  }
}
