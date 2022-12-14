import DomainViews from '../../domain_abstract/view/DomainViews.js';
import ToolbarButtonView from './ToolbarButtonView.js';

export default class ToolbarView extends DomainViews {
  constructor(opts = {}, config) {
    super(opts, config);
    this.config = { editor: opts.editor || '', em: opts.em };
    this.listenTo(this.collection, 'reset', this.render);
  }
}

ToolbarView.prototype.itemView = ToolbarButtonView;
