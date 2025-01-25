<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gift Graph</title>
    <script src="https://unpkg.com/cytoscape/dist/cytoscape.min.js"></script>
    <style>
        #cy {
            width: 100%;
            height: 1000px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div id="cy"></div>
<script>
    fetch('/api/get-gift-graph?userId=1180&depth=4')
        .then(response => response.json())
        .then(data => {
            const elements = [];

            data.nodes.forEach(node => {
                elements.push({
                    data: {
                        id: node.id,
                        label: node.label
                    }
                });
            });

            data.edges.forEach(edge => {
                elements.push({
                    data: {
                        source: edge.source,
                        target: edge.target,
                        type: edge.type,
                        gift_id: edge.gift_id
                    }
                });
            });

            const cy = cytoscape({
                container: document.getElementById('cy'),
                elements: elements,
                style: [
                    {
                        selector: 'node',
                        style: {
                            'label': 'data(label)',
                            'background-color': '#0074D9',
                            'color': '#000',
                            'text-valign': 'center',
                            'width': 40,
                            'height': 40,
                            'font-size': '12px',
                            'text-outline-width': 1,
                            'text-outline-color': '#fff'
                        }
                    },
                    {
                        selector: 'edge[type="sent"]',
                        style: {
                            'line-color': '#FF4136',
                            'target-arrow-color': '#FF4136',
                            'target-arrow-shape': 'triangle',
                            'curve-style': 'bezier',
                            'width': 2,
                            'label': 'data(gift_id)',
                            'font-size': '10px',
                            'text-rotation': 'autorotate',
                            'color': '#000'
                        }
                    },
                    {
                        selector: 'edge[type="received"]',
                        style: {
                            'line-color': '#2ECC40',
                            'target-arrow-color': '#2ECC40',
                            'target-arrow-shape': 'triangle',
                            'curve-style': 'bezier',
                            'width': 2,
                            'label': 'data(gift_id)',
                            'font-size': '10px',
                            'text-rotation': 'autorotate',
                            'color': '#000'
                        }
                    }
                ],
                layout: {
                    name: 'cose',
                    animate: true
                }
            });

            cy.on('mouseover', 'edge', (event) => {
                const edge = event.target;
                const type = edge.data('type') === 'sent' ? 'Sent' : 'Received';
                const giftId = edge.data('gift_id');

            });
        });
</script>
</body>
</html>
