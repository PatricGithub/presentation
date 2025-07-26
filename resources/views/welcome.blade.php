<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analogy Generation in Chess</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #000;
            color: #fff;
            overflow: hidden;
            height: 100vh;
            position: relative;
        }

        .presentation-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .slide {
            position: absolute;
            width: 90%;
            max-width: 1200px;
            height: 85vh;
            padding: 80px;
            opacity: 0;
            transform: translateX(100px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .slide.active {
            opacity: 1;
            transform: translateX(0);
        }

        .slide.prev {
            transform: translateX(-100px);
        }

        h1 {
            font-size: 4em;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 0.5em;
            background: linear-gradient(135deg, #fff 0%, #888 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        h2 {
            font-size: 3em;
            font-weight: 600;
            margin-bottom: 0.8em;
            color: #fff;
        }

        h3 {
            font-size: 1.8em;
            font-weight: 600;
            margin-bottom: 0.5em;
            color: #00D4FF;
        }

        p, li {
            font-size: 1.4em;
            line-height: 1.6;
            margin-bottom: 0.8em;
            color: #e0e0e0;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 0.5em;
            padding-left: 1.5em;
            position: relative;
        }

        li:before {
            content: "•";
            color: #00D4FF;
            font-size: 1.5em;
            position: absolute;
            left: 0;
            top: -10px;
        }

        strong {
            color: #00D4FF;
            font-weight: 600;
        }

        em {
            font-style: italic;
            color: #888;
        }

        .navigation {
            position: fixed;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            z-index: 1000;
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 24px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            font-size: 16px;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 1000;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #00D4FF, #0066FF);
            transition: width 0.6s ease;
        }

        .slide-number {
            position: fixed;
            top: 40px;
            right: 60px;
            font-size: 0.9em;
            color: #888;
        }

        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .highlight {
            background: linear-gradient(135deg, #222, #0066FF);
            padding: 40px;
            border-radius: 20px;
            margin: 20px 0;
        }

        .stat {
            font-size: 3em;
            font-weight: 700;
            color: #00D4FF;
            margin-bottom: 0.2em;
        }

        .subtitle {
            font-size: 1.6em;
            color: #888;
            margin-bottom: 2em;
            font-weight: 300;
        }

        .author {
            font-size: 1.2em;
            color: #888;
            margin-top: 2em;
        }

        code {
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'SF Mono', Monaco, monospace;
        }

        .formula {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            font-family: 'SF Mono', Monaco, monospace;
            margin: 20px 0;
            text-align: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin: 40px 0;
        }

        .metric-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .metric-visual {
            width: 900px;
            height: auto;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(0, 212, 255, 0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .metric-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .metric-description {
            font-size: 1.2em;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="progress">
        <div class="progress-bar" id="progressBar"></div>
    </div>
    
    <div class="slide-number" id="slideNumber">1 / 25</div>
    
    <div class="presentation-container">
        <!-- Slide 1: Title -->
        <div class="slide active">
            <h1 style="font-size: 6.5rem">Analogy <br> Generation <br>in Chess</h1>
            <p class="author"><strong>Patric Pfoertner & Penka Hristova</strong><br>
            Department of Cognitive Science and Psychology<br>
            New Bulgarian University, Sofia, Bulgaria</p> 
        </div>

        <!-- Slide 2: The Fundamental Question -->
        <div class="slide">
            <h1>Central Debate in Chess Cognition</h1>
            <div class="highlight">
                <h3>Two Point of Views</h3>
                <ul>
                    <li><strong>Pattern Recognition View</strong>: Experts recognize chunks/templates (Chase & Simon, 1973)</li>
                    <li><strong>Experience Recognition View</strong>: Experts use analogies based on abstract relations (Linhares & Brum, 2007)</li>
                </ul>
            </div>
            <p><strong>Key Question</strong>: Do chess experts rely on perceptual patterns or structural relationships when generating analogies?</p>
        </div>

        <!-- Slide 3: Theoretical Framework 
        <div class="slide">
            <h1>Analogical Reasoning: Surface vs. Structure</h1>
            <div class="two-column">
                <div>
                    <h3>Structure Mapping Theory (Gentner, 1983)</h3>
                    <ul>
                        <li>Analogies involve mapping relational structures between domains</li>
                        <li>Surface features ≠ necessary for analogy</li>
                        <li>Structural similarity = shared relations</li>
                    </ul>
                </div>
                <div>
                    <h3>The Retrieval Gap (Holyoak, 2012)</h3>
                    <ul>
                        <li>Superficial features dominate retrieval from memory</li>
                        <li>Structural retrieval requires expertise/abstraction</li>
                    </ul>
                </div>
            </div>
        </div>
-->
        <!-- Slide 4: Chess Expertise Theories -->
        <div class="slide">
            <h1>The Competing Accounts</h1>
            <div class="two-column">
                <div>
                    <h3>Chunking/Template Theory</h3>
                    <ul>
                        <li>Pieces-on-squares (POS) patterns</li>
                        <li>Perceptual proximity crucial</li>
                        <li>~50,000-100,000 chunks in expert memory</li>
                    </ul>
                </div>
                <div>
                    <h3>Experience Recognition Theory</h3>
                    <ul>
                        <li>Abstract relational understanding</li>
                        <li>Strategic similarity > perceptual similarity</li>
                        <li>Analogical transfer between positions</li>
                    </ul>
                </div>
            </div>
            <br>
            <p class="subtitle"><strong>Our Study</strong>: First to use analogy generation task to test these theories</p>
        </div>

        <!-- Slide 5: Research Questions & Hypotheses -->
        <div class="slide">
            <h1>Research Question</h1>
            <ul>
                <li>Do experts in chess use analogies to transfer solutions from past episodes (Linhares and Brum, 2007) and will these analogies contain less perceptual overlap and will they rely more on higher-order relations, hence relying on the structural properties of the target chess configuration, rather than partial relational matches?</li>
            </ul>
            <br>
            <h2>Hypotheses</h2>
            <ul>
                <li><strong>H1</strong>: Experts will generate more analogies with less perceptual overlap</li>
                <li><strong>H2</strong>: Experts will retrieve analogies from memory; novices from fantasy</li>
                <li><strong>H3</strong>: Experts will be more confident and satisfied with their analogies, steaming from higher-order relational commonalities</li>
            </ul>
        </div>

        <!-- Slide 6: Method - Participants -->
        <div class="slide">
            <h1>Participants (N = 67)</h1>
            <div class="two-column">
                <div>
                    <h3>Advanced Players (n = 32)</h3>
                    <ul>
                        <li>Age: <em>M</em> = 54.03, <em>SD</em> = 19.53</li>
                        <li>ELO: <em>M</em> = 1151.88, <em>SD</em> = 204.97</li>
                        <li>Started chess: <em>M</em> = 10.91 years</li>
                    </ul>
                </div>
                <div>
                    <h3>Novices (n = 35)</h3>
                    <ul>
                        <li>Age: <em>M</em> = 36.20, <em>SD</em> = 10.50</li>
                        <li>ELO: <em>M</em> = 640.11, <em>SD</em> = 125.02</li>
                        <li>Started chess: <em>M</em> = 17.11 years</li>
                    </ul>
                </div>
            </div>
            <p class="subtitle"><em>Classification via 3 chess puzzles (2/3 correct = advanced)</em></p>
        </div>

        <!-- Slide 7: Method - Procedure -->
        <div class="slide">
            <h1>Analogy Generation Task</h1>
            <ul>
                <li><strong>10 chess positions</strong> from Linhares & Brum (2007)</li>
                <li>Create analogous position on empty board</li>
                <li>Report source: <strong>memory vs. fantasy</strong></li>
                <li>Rate <strong>confidence</strong> and <strong>satisfaction</strong> (1-5 scale)</li>
                <li>Provide written explanation</li>
            </ul>
            <div class="highlight">
                <h3>Judges</h3>
                <p>3 independent chess experts (ELO 1940-2057)<br>
                Inter-rater agreement: 76.87%<br>
                Cohen's κ = 0.537 (Judges 1-2)</p>
            </div>
        </div>

        <!-- Slide 8: Method - Measuring Perceptual Overlap -->
        <div class="slide">
            <h1>Novel Objective Metrics</h1>
            <div class="metrics-grid">
                <div class="metric-item">
                    <div class="metric-visual">
                        <img src="{{ asset('image-1.png')}}" alt="Pixel-by-pixel overlap visualization" class="metric-image">
                    </div>
                    <div class="metric-description">
                        <strong>1. Pixel-by-pixel overlap</strong><br>
                        Identical pieces in identical positions
                    </div>
                </div>
                 
            </div>
        </div>
<div class="slide">
            <h1>Novel Objective Metrics</h1>
            <div class="metrics-grid"> 
                
                <div class="metric-item">
                    <div class="metric-visual">
                        <img src="{{ asset('image-2.png')}}" alt="Non-empty pixel overlap visualization" class="metric-image">
                    </div>
                    <div class="metric-description">
                        <strong>2. Non-empty pixel overlap</strong><br>
                        Focus on piece positions only
                    </div>
                </div>
            </div>
         </div>
         <!-- Slide 8: Method - Measuring Perceptual Overlap -->
        <div class="slide">
            <h1>Novel Objective Metrics</h1>
            <div class="metrics-grid"> 
                
                <div class="metric-item">
                    <div class="metric-visual">
                        <img src="{{ asset('image-3.png')}}" alt="Piece configuration fidelity visualization" class="metric-image">
                    </div>
                    <div class="metric-description">
                        <strong>3. Piece configuration fidelity</strong><br>
                        Same distribution of piece types
                    </div>
                </div> 
            </div>
         </div>

         <!-- Slide 8: Method - Measuring Perceptual Overlap -->

        <!-- Slide 9: Results - Analogy Generation Frequency -->
        <div class="slide">
            <h1>Experts Generate More Analogies</h1>
            <p><strong>Overall</strong>: 299/670 configurations classified as analogies (44.6%)</p>
            <div class="highlight">
                <h3>By Expertise:</h3>
                <div class="two-column">
                    <div>
                        <p class="stat">56.88%</p>
                        <p>Advanced (182/320)</p>
                    </div>
                    <div>
                        <p class="stat">33.43%</p>
                        <p>Novices (117/350)</p>
                    </div>
                </div>
            </div>
            <p>χ²(1, <em>N</em> = 670) = 37.19, <em>p</em> < .001, Cramér's <em>V</em> = 0.23</p>
            <p><strong>Effect</strong>: Experts 1.7x more likely to generate analogies</p>
        </div>

        <!-- Slide 10: Results - Source of Analogies -->
        <div class="slide">
            <h1>Memory vs. Fantasy Generation</h1>
            <div class="two-column">
                <div>
                    <h3>Advanced Players:</h3>
                    <ul>
                        <li>Memory: <strong>63.7%</strong></li>
                        <li>Fantasy: 36.3%</li>
                    </ul>
                </div>
                <div>
                    <h3>Novices:</h3>
                    <ul>
                        <li>Memory: 34.2%</li>
                        <li>Fantasy: <strong>65.8%</strong></li>
                    </ul>
                </div>
            </div>
            <p>χ²(1, <em>N</em> = 299) = 23.75, <em>p</em> < .001</p>
            <p class="subtitle"><strong>Key Finding</strong>: Experts retrieve from episodic memory; novices construct imaginatively</p>
        </div>

        <!-- Slide 11: Results - Perceptual Overlap -->
        <div class="slide">
            <h1>Lower Surface Similarity in Expert Analogies</h1>
            <div class="two-column">
                <div>
                    <h3>Pixel-by-pixel Overlap:</h3>
                    <ul>
                        <li>Advanced: <em>M</em> = 0.78, <em>SD</em> = 0.07</li>
                        <li>Novices: <em>M</em> = 0.81, <em>SD</em> = 0.09</li>
                        <li><em>U</em> = 8324.00, <em>p</em> = .001, <em>d</em> = -0.38</li>
                    </ul>
                </div>
                <div>
                    <h3>Non-empty Pixel Overlap:</h3>
                    <ul>
                        <li>Advanced: <em>M</em> = 0.09, <em>SD</em> = 0.22</li>
                        <li>Novices: <em>M</em> = 0.22, <em>SD</em> = 0.25</li>
                        <li><em>U</em> = 6040.00, <em>p</em> < .001, <em>d</em> = -0.53</li>
                    </ul>
                </div>
            </div>
            <p class="subtitle"><em>Experts create analogies with less literal copying</em></p>
        </div>

        <!-- Slide 12: Results - Representational Economy -->
        <div class="slide">
            <h1>Experts Use Fewer Pieces</h1>
            <div class="highlight">
                <h3>Pieces Used:</h3>
                <ul>
                    <li>Advanced: <em>M</em> = 7.22</li>
                    <li>Novices: <em>M</em> = 11.10</li>
                    <li><em>U</em> = 29,456.50, <em>p</em> < .001, <em>r</em> = -0.47</li>
                </ul>
            </div>
            <div class="highlight">
                <h3>Piece-Count Disparity:</h3>
                <ul>
                    <li>Advanced: <em>M</em> = -3.30 pieces</li>
                    <li>Novices: <em>M</em> = -0.38 pieces</li>
                    <li><em>U</em> = 5548.50, <em>p</em> < .001, <em>d</em> = -0.95</li>
                </ul>
            </div>
            <p class="subtitle"><em>Experts achieve strategic equivalence with ~30% fewer pieces</em></p>
        </div>

        <!-- Slide 13: Results - Confidence & Satisfaction -->
        <div class="slide">
            <h1>Higher Subjective Ratings for Experts</h1>
            <div class="two-column">
                <div>
                    <h3>Confidence (5-point scale):</h3>
                    <ul>
                        <li>Advanced: Mean rank = 3.87</li>
                        <li>Novices: Mean rank = 2.60</li>
                        <li><em>U</em> = 91,934.50, <em>p</em> < .001</li> 
                    </ul>
                </div>
                <div>
                    <h3>Satisfaction:</h3>
                    <ul>
                        <li>Advanced: Mean rank = 3.74</li>
                        <li>Novices: Mean rank = 2.69</li>
                        <li><em>U</em> = 82,177.50, <em>p</em> < .001</li> 
                    </ul>
                </div>
            </div> 
        </div>

        <!-- Slide 14: Results - Key Correlations -->
        <div class="slide">
            <h1>Structural Features Predict Confidence</h1>
            <h3>Negative correlations with confidence:</h3>
            <ul>
                <li>Non-empty pixel overlap: <em>rs</em> = -0.208, <em>p</em> < .001</li>
                <li>Number of pieces: <em>rs</em> = -0.179, <em>p</em> = .002</li>
                <li>Exact piece matching: <em>rs</em> = -0.156, <em>p</em> = .007</li>
            </ul>
            <h3>With ELO rating:</h3>
            <ul>
                <li>Non-empty overlap: <em>rs</em> = -0.307, <em>p</em> < .001</li>
                <li>Pieces used: <em>rs</em> = -0.302, <em>p</em> < .001</li>
                <li>Configuration fidelity: <em>rs</em> = -0.323, <em>p</em> < .001</li>
            </ul>
            <p class="subtitle"><em>Higher expertise → Less surface similarity → Greater confidence</em></p>
        </div>

        <!-- Slide 15: Results - Processing Efficiency -->
        <div class="slide">
            <h1>Experts Generate Analogies Faster</h1>
            <div class="highlight">
                <h3>Time to Generate:</h3>
                <ul>
                    <li>Experts faster by 15.19 seconds</li>
                    <li><em>U</em> = 2663.00, <em>p</em> < .001, <em>d</em> = -1.58</li>
                </ul>
            </div>
            <h3>Correlations with time:</h3>
            <ul>
                <li>Satisfaction: <em>rs</em> = -0.286, <em>p</em> < .001</li>
                <li>Confidence: <em>rs</em> = -0.337, <em>p</em> < .001</li>
                <li>ELO rating: <em>rs</em> = -0.477, <em>p</em> < .001</li>
            </ul>
            <p class="subtitle"><em>Despite generating more complex structural analogies</em></p>
        </div>

        <!-- Slide 16: Summary of Findings -->
        <div class="slide">
            <h1>Support for Experience Recognition</h1>
            <ul>
                <li><strong>Experts generate 70% more analogies</strong> than novices</li>
                <li><strong>Memory-based retrieval</strong> (64%) vs. fantasy construction</li>
                <li><strong>Lower perceptual overlap</strong> (78% vs. 81% pixel match)</li>
                <li><strong>Greater abstraction</strong>: 30% fewer pieces used</li>
                <li><strong>Higher confidence</strong> despite lower surface similarity</li>
                <li><strong>Faster generation</strong> of structurally complex analogies</li>
            </ul>
            <p class="subtitle"><strong>Conclusion</strong>: Expertise may transform analogical reasoning from surface-based to structure-based processing</p>
        </div>
 <div class="slide">
            <h1>Verbal Explanations of Analogies</h1>
            <p>299 German-language verbal responses were systematically analysed across multiple cognitive dimensions</p>
            <div class="highlight">
                <h3>Solution: AI-assisted content analysis using Qwen3-235B-A22B-07-25</h3>
                <ul>
                    <li>Superior multilingual capabilities</li>
                    <li>Extensive German language training</li>
                    <li>Domain-specific chess terminology proficiency</li>
                </ul>
            </div>
            <p><strong>Objective</strong>: Examine HOW experts vs. novices explain their analogical reasoning</p>
            <p><strong>Sample</strong>: 182 advanced players, 117 novices with complete verbal responses</p>
        </div>

        <!-- Slide 22: Measurement Framework -->
       <div class="slide">
        <h1>Categorical Dimensions</h1>
        <div class="two-column">
            <div>
                <ul>
                    <li><strong>Structural vs. Surface Focus</strong>: 
                        <br><em>Structural:</em> Piece relationships, strategic purposes, abstract patterns
                        <br><em>Surface:</em> Piece locations, colors, specific squares without context
                        <br><em>Mixed:</em> Combination of both approaches
                    </li>
                    <li><strong>Abstraction Level</strong>: 
                        <br><em>Abstract:</em> General principles, strategic concepts, theoretical frameworks
                        <br><em>Concrete:</em> Specific pieces, exact positions, literal observations
                        <br><em>Mixed:</em> Abstract concepts with concrete details
                    </li>
                    <li><strong>Strategic vs. Tactical Orientation</strong>: 
                        <br><em>Strategic:</em> Long-term planning, positional concepts, general principles
                        <br><em>Tactical:</em> Short-term combinations, specific moves, immediate threats
                        <br><em>Mixed:</em> Both strategic and tactical elements
                    </li>
                </ul>
            </div>
            <div>
                <ul>
                    <li><strong>Description Type</strong>: 
                        <br><em>Relational:</em> How pieces work together, coordinate, support each other
                        <br><em>Positional:</em> Where pieces are located without explaining relationships
                        <br><em>Mixed:</em> Combination of relational and positional elements
                    </li>
                    <li><strong>Chess Terminology Sophistication</strong>: 
                        <br><em>Sophisticated:</em> Advanced chess terminology used correctly and precisely
                        <br><em>Basic:</em> Standard chess terms used simply
                        <br><em>Informal:</em> Casual language, non-standard terms, colloquial expressions
                    </li>
                    <li><strong>Reasoning Complexity</strong>: 
                        <br><em>Complex:</em> Multi-step reasoning, multiple factors, sophisticated analysis
                        <br><em>Simple:</em> Basic cause-effect reasoning, few factors considered
                        <br><em>Minimal:</em> Little to no reasoning, primarily descriptive
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="slide">
        <h1>Quantitative Language Indicators</h1>
        <div class="two-column">
            <div>
                <ul>
                    <li><strong>Chess Terms Count</strong>: Number of technical chess vocabulary items <span class="emphasis">(e.g., German: "Gabel," "Fesselung," "Bauernstruktur")</span></li>
                    <li><strong>Causal Words Count</strong>: Number of reasoning indicators showing logical connections <span class="emphasis">(e.g., German: "weil," "deshalb," "führt zu")</span></li>
                    <li><strong>Abstract Concepts Count</strong>: Number of strategic/positional concepts referenced <span class="emphasis">(e.g., "Kontrolle," "Druck," "Schwäche," "Initiative")</span></li>
                    <li><strong>Positional References Count</strong>: Number of specific location references <span class="emphasis">(e.g., "auf h8," "hier," "dort")</span></li>
                </ul>
            </div>
            <div>
                <ul>
                     <li><strong>Total Expert Features</strong>: <span class="scale">Sum of binary expert indicators across all six dimensions (0-6 scale)</span></li>
                    <li><strong>Language Complexity Score</strong>: Composite measure combining chess terms, causal words, and abstract concepts</li>
                    <li><strong>Primary Classification</strong>: Overall expert-like vs. novice-like vs. intermediate classification based on response patterns</li>
                </ul>
            </div>
        </div>
    </div>
        <!-- Slide 23: Dramatic Linguistic Differences -->
        <div class="slide">
            <h1>Results Categorical Dimensions</h1>
            <div class="highlight">
                <h3>Structural Focus:</h3>
                <ul>
                    <li>Advanced: <strong>96.2%</strong> structural</li>
                    <li>Novices: <strong>0.0%</strong> structural</li>
                    <li>χ²(1) = 267.32, <em>p</em> < .001, <strong>V = 0.95</strong></li>
                </ul>
            </div>
            <div class="two-column">
                <div>
                    <h3>Abstract Language:</h3>
                    <ul>
                        <li>Advanced: <strong>96.2%</strong></li>
                        <li>Novices: <strong>6.8%</strong></li>
                        <li>χ²(2) = 239.28, <em>p</em> < .001, <strong>V = 0.89</strong></li>
                    </ul>
                </div>
                <div>
                    <h3>Strategic Orientation:</h3>
                    <ul>
                        <li>Advanced: <strong>86.3%</strong></li>
                        <li>Novices: <strong>6.0%</strong></li>
                        <li>χ²(1) = 182.11, <em>p</em> < .001, <strong>V = 0.78</strong></li>
                    </ul>
                </div>
            </div>
         </div>

        <!-- Slide 24: Quantitative Language Markers -->
        <div class="slide">
            <h1>Linguistic Sophistication Metrics</h1>
            <div class="highlight">
                <h3>Chess Terms Usage:</h3>
                <ul>
                    <li>Advanced: <em>Mdn</em> = 2.00, <em>M</em> = 2.47</li>
                    <li>Novices: <em>Mdn</em> = 0.00, <em>M</em> = 0.83</li>
                    <li><em>U</em> = 16,224.00, <em>p</em> < .001, <strong>r = -0.52</strong></li>
                </ul>
            </div>
            <div class="two-column">
                <div>
                    <h3>Abstract Concepts:</h3>
                    <ul>
                        <li>Advanced: <em>M</em> = 2.04 (<em>SD</em> = 1.34)</li>
                        <li>Novices: <em>M</em> = 0.10 (<em>SD</em> = 0.31)</li>
                        <li><em>U</em> = 20,292.00, <em>p</em> < .001, <strong>r = -0.91</strong></li>
                    </ul>
                </div>
                <div>
                    <h3>Composite Expert Features (0-6):</h3>
                    <ul>
                        <li>Advanced: <em>Mdn</em> = 6.00, <em>M</em> = 5.58</li>
                        <li>Novices: <em>Mdn</em> = 0.00, <em>M</em> = 0.33</li>
                        <li><em>U</em> = 20,793.50, <em>p</em> < .001, <strong>r = -0.95</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Slide 25: Convergent Evidence -->
        <div class="slide">
            <h1>Linguistic Analysis Validates Behavioral Findings</h1>
            <h3>Expert Language Examples:</h3>
            <ul>
                <li><em>"Die Bauernstruktur schafft langfristige Schwächen auf den dunklen Feldern"</em><br>
                (Pawn structure creates long-term weaknesses on dark squares)</li>
                <li><em>"Figurenkoordination ermöglicht dominante Zentrumskontrolle"</em><br>
                (Piece coordination enables dominant center control)</li>
            </ul>
            <h3>Novice Language Examples:</h3>
            <ul>
                <li><em>"Der Bauer steht auf e4 und der König auf g8"</em><br>
                (The pawn is on e4 and the king is on g8)</li>
                <li><em>"Das Pferd springt da hin"</em><br>
                (The horse jumps there)</li>
            </ul>
            <p class="subtitle"><strong>Key Finding</strong>: Language analysis confirms experts encode analogies through abstract relations, not surface patterns</p>
            <p><strong>All effect sizes V > 0.78</strong></p>
        </div>
    </div>
        <!-- Slide 17: Theoretical Implications -->
        <div class="slide">
            <h1>Supporting Structural Accounts</h1>
            <h3>Challenges to Chunking Theory:</h3>
            <ul>
                <li>POS patterns insufficient to explain results</li>
                <li>Abstract relations, not proximity, drive expert analogies</li>
            </ul>
            <h3>Support for Experience Recognition:</h3>
            <ul>
                <li>Direct retrieval of strategically similar episodes</li>
                <li>Minimal perceptual overlap between analogies</li>
            </ul>
            <h3>Broader Implications:</h3>
            <ul>
                <li>Domain knowledge enables structural encoding</li>
                <li>Aligns with naturalistic studies (Dunbar & Blanchette, 2001)</li>
            </ul>
        </div>

        <!-- Slide 18: Methodological Contributions -->
        <div class="slide">
            <h1>Novel Contributions</h1> 
            <ul>
                <li><strong>First analogy generation study</strong> in chess expertise</li>
                <li><strong>Objective computational metrics</strong> for surface similarity</li>
                <li><strong>Source attribution</strong> (memory vs. fantasy) methodology</li>
                <li><strong>Multiple convergent measures</strong> of structural reasoning</li>
            </ul> 
        </div>

        <!-- Slide 19: Limitations & Future Directions -->
        <div class="slide">
            <h1>Limitations & Next Steps</h1> 
            <ul>
                <li>Self-report of memory source (familiarity vs. episodic?)</li>
                <li>Gender imbalance (94% male)</li>
                <li>Puzzle-based expertise classification</li>
            </ul>
            <h3>Future Research:</h3>
            <ul>
                <li>Developmental trajectory of structural reasoning</li>
                <li>Cross-domain generalization</li>
                <li>Neural correlates of expert analogy</li>
                <li>Training interventions for structural encoding</li>
            </ul>
        </div>

        <!-- Slide 20: Conclusions -->
        <div class="slide">
            <h1>Key Takeaways</h1>
            <p><strong>Chess expertise fundamentally transforms analogical reasoning</strong></p>
            <ul>
                <li>From <strong>surface features</strong> to <strong>structural relations</strong></li>
                <li>From <strong>imagination</strong> to <strong>memory retrieval</strong></li>
                <li>From <strong>literal copying</strong> to <strong>abstract transfer</strong></li>
            </ul>
            <h3>Practical Implications:</h3>
            <ul>
                <li>Chess instruction should emphasize relational understanding</li>
                <li>Expert knowledge = interconnected abstract schemas</li>
                <li>Analogical reasoning as core to expertise, not peripheral</li>
            </ul>
            <p class="subtitle"><em>"The very blue that fills the whole sky of cognition"</em> - Hofstadter (2001)</p>
        </div>

        <!-- Slide 21: AI-Assisted Linguistic Analysis -->
       

    <div class="navigation">
        <button class="nav-btn" id="prevBtn" onclick="changeSlide(-1)">Previous</button>
        <button class="nav-btn" id="nextBtn" onclick="changeSlide(1)">Next</button>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(n) {
            slides.forEach(slide => {
                slide.classList.remove('active', 'prev');
            });
            
            currentSlide = (n + totalSlides) % totalSlides;
            
            if (currentSlide > 0) {
                slides[currentSlide - 1].classList.add('prev');
            }
            
            slides[currentSlide].classList.add('active');
            
            // Update progress bar
            const progress = ((currentSlide + 1) / totalSlides) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            
            // Update slide number
            document.getElementById('slideNumber').textContent = `${currentSlide + 1} / ${totalSlides}`;
            
            // Update navigation buttons
            document.getElementById('prevBtn').disabled = currentSlide === 0;
            document.getElementById('nextBtn').disabled = currentSlide === totalSlides - 1;
        }

        function changeSlide(direction) {
            showSlide(currentSlide + direction);
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' && currentSlide > 0) {
                changeSlide(-1);
            } else if (e.key === 'ArrowRight' && currentSlide < totalSlides - 1) {
                changeSlide(1);
            }
        });

        // Touch navigation for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            if (touchEndX < touchStartX - 50 && currentSlide < totalSlides - 1) {
                changeSlide(1);
            }
            if (touchEndX > touchStartX + 50 && currentSlide > 0) {
                changeSlide(-1);
            }
        }

        // Initialize
        showSlide(0);
    </script>
</body>
</html>