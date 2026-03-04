let handles = [];

export const attach = function(node, handle, event = "click", group = "__default", options) {
    const handleWrapper = e => {
        handle(e, () => {
            node.removeEventListener(event, handleWrapper);
        })
    }

    node.addEventListener(event, handleWrapper, options);
    
    handles.push({node, event, group, handle: handleWrapper});
}

export const autodetach = (handle) => {
    return (e, detach) => {
        detach();
        handle(e);
    }
}

export const attachQuery = function(query, handle, event = "click", group) {
    document.querySelectorAll(query).forEach(node => {
        attach(node, handle, event, group);
    });
}

const detach = function(predicate) {
    const [toRemove, toKeep] = handles.reduce(([toRemove, toKeep], handle) => {
        if (predicate(handle)) {
            toRemove.push(handle);
        } else {
            toKeep.push(handle);
        }
        return [toRemove, toKeep];
    }, [[], []]);

    toRemove.forEach(({node, event, handle}) => node.removeEventListener(event, handle));
    handles = toKeep;
}

export const detachAll = function(){
    detach(() => true);
}

export const detachGroup = function(group) {
    detach(handle => handle.group === group);
}

export const detachNode = function(node) {
    detach(handle => handle.node === node);
}