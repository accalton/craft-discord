const getComponentContext = () => require.context('./components', true, /\.(\/index)?jsx$/);

const getComponent = (context, componentName) => {
    const findComponentDir = new RegExp(`${componentName}(/index)?\\.jsx`);
    const componentDir = context.keys().find(path => path.match(findComponentDir));
    if (!componentDir) {
        throw new Error(`No directory of file found for component ${componentName}`);
    }
    return context(componentDir).default;
}

const getSprinkleNodes = () => Array.from(document.querySelectorAll('[data-react]'));

export function getSprinklesComponent() {
    const nodes = getSprinkleNodes();
    const context = getComponentContext();

    return nodes.map(node => {
        const Comp = getComponent(context, node.dataset.react);
        const props = node.dataset.reactProps ? JSON.parse(node.dataset.reactProps) : {};
        props.children = node.innerHTML;
        return { Comp, props, node };
    });
}