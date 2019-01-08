import { mergeData } from 'vue-functional-data-merge';
import { prefixPropName, unPrefixPropName } from '../../utils/strings';
import copyProps from '../../utils/copyProps';
import pluckProps from '../../utils/pluckProps';
import { assign } from '../../utils/object';
import cardMixin from '../../mixins/component/card';
import CardBody, { props as bodyProps } from './card-body';
import CardHeader, { props as headerProps } from './card-header';
import CardFooter, { props as footerProps } from './card-footer';
import CardImg, { props as imgProps } from './card-img';
import { defaultCollapseClick, defaultCloseClick, props as actionProps } from './card-actions';
import { matches } from '../../utils/dom';

const cardImgProps = copyProps(imgProps, prefixPropName.bind(null, 'img'));
cardImgProps.imgSrc.required = false;

export const props = assign(
    {},
    bodyProps,
    headerProps,
    footerProps,
    cardImgProps,
    actionProps,
    copyProps(cardMixin.props),
    {
        align: {
            type: String,
            default: null
        },
        cardVariant: {
            type: String,
            default: null
        },
        noBody: {
            type: Boolean,
            default: false
        }
    }
);

export default {
    functional: true,

    props,

    render (h, { props, data, slots, listeners }) {
        // The order of the conditionals matter.
        // We are building the component markup in order.
        let childNodes = [];
        const $slots = slots();

        let img = props.imgSrc
            ? h(CardImg, {
                props: pluckProps(
                    cardImgProps,
                    props,
                    unPrefixPropName.bind(null, 'img')
                )
            })
            : null;

        if (img) {
            // Above the header placement.
            if (props.imgTop || ! props.imgBottom) {
                childNodes.push(img);
            }
        }

        const hasCardActions = props.collapse || props.expandable || props.closeable;
        const showHeader = !! (props.header || $slots.header) || hasCardActions;
        if (showHeader) {
            const collapseClick = listeners['collapse-click'] || defaultCollapseClick;
            const closeClick = listeners['close-click'] || defaultCloseClick;
            const headerClick = listeners['header-click'] || null;

            childNodes.push(
                h(CardHeader,
                    {
                        props: { ...pluckProps(actionProps, props), ...pluckProps(headerProps, props) },
                        on: {
                            'collapse-click': event => collapseClick(event),
                            'close-click': event => closeClick(event),
                            click: event => {
                                event.preventDefault();

                                if (headerClick === null) {
                                    return;
                                }

                                if (matches(event.target, '.card-collapse') || matches(event.target, '.card-expand') || matches(event.target, '.card-close')) {
                                    return;
                                }

                                // No card actions were clicked and an event handler was given,
                                // call the event handler
                                headerClick(event);
                            }
                        }
                    },
                    $slots.header));
        }

        if (props.noBody) {
            childNodes.push($slots.default);
        } else {
            childNodes.push(
                h(CardBody, { props: pluckProps(bodyProps, props) }, $slots.default)
            );
        }

        if (props.footer || $slots.footer) {
            childNodes.push(
                h(CardFooter, { props: pluckProps(footerProps, props) }, $slots.footer)
            );
        }

        if (img && props.imgBottom) {
            // Below the footer placement.
            childNodes.push(img);
        }

        return h(
            props.tag,
            mergeData(data, {
                staticClass: 'card',
                class: {
                    [`text-${props.align}`]: !! props.align,
                    [`bg-${props.bgVariant}`]: !! props.bgVariant,
                    [`border-${props.borderVariant}`]: !! props.borderVariant,
                    [`text-${props.textVariant}`]: !! props.textVariant,
                    ['has-card-actions']: hasCardActions,
                    [`card-${props.cardVariant}`]: !! props.cardVariant,
                }
            }),
            childNodes
        );
    }
};