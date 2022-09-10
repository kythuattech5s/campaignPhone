import Backbone from 'https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.4.1/backbone-min.js';

export default class ToolbarButton extends Backbone.Model {
  defaults() {
    return {
      command: '',
      attributes: {},
    };
  }
}
